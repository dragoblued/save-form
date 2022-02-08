<?php

namespace App\Services;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\File;
use stdClass;
use App\Translation;

class Uploader
{
	private $request;

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function create ($model)
	{
		$request = $this->request;
		$replace = [];
		$folders = $model::FILES;

		foreach ($folders as $field => $file) {
			if($request->has($field)) {
				$replace[$field] = 'ID_fake';
			}
		}

		$data = $request->all();
		$data = array_merge($data, $replace);

		$created = $model::create($data);
		return $this->update($created);
	}

	public function update ($item)
	{
		$request = $this->request;
		$replace = [];
		$settings = $this->settings($item);

		foreach ($settings as $field => $set) {
			if($request->has($field)) {
				$replace[$field] = $this->upload($item, $field, $set);
			}
		}
		$data = $request->all();
		$data = array_merge($data, $replace);

        // if(!app()->isLocale('ru')) {
        //     foreach ($item::TRANSLATED as $field) {
        //         Translation::updateOrCreate([
        //             'locale' => app()->getLocale(),
        //             'table' => $item->getTable(),
        //             'o_id' => $item->id,
        //             'field' => $field,
        //         ],
        //         [
        //             'value' => $data[$field],
        //         ]);
        //         unset($data[$field]);
        //     }
        // }
        $item->fill($data);
        $item->save();

		return $item;
	}

	public function files ($item)
	{
		$request = $this->request;
		if($request->filled('delete')) {
			$this->slice($item, $request->field, $request->delete);
			return response()->json('Deleted');
		}
		if($request->filled('shuffle')) {
			$this->shuffle($item, $request->field, $request->shuffle);
			return response()->json('Shuffled');
		}

		return false;
	}

	protected function upload ($item, string $field, stdClass $settings)
	{
		$request = $this->request;
		$folder = $this->checkDir($settings->folder);
		$files = [];
		$fnames = [];
		if($settings->multiple) {
			$files = $request[$field];
			$folder = $this->checkDir("{$settings->folder}{$item->id}/");
		}
		else {
			$files[] = $request[$field];
		}
		foreach ($files as $file) {
			$ext = $file->getClientOriginalExtension();
			$naming = $settings->multiple ? null : $item->id;
			$lang = isset($settings->lang) ? $settings->lang : null;
			$fname = $this->generateName($folder, $naming, $ext, $lang);
			$file = $file->move($folder, $fname);
			$this->sizers($settings, $file, (!$naming ? "{$settings->folder}{$item->id}/" : $settings->folder), $fname);
			$fnames[] = $fname;
		}

		if(!$settings->multiple) {
			$fnames = $fnames[0];
		}
		elseif(is_array($item[$field])) {
			$fnames = array_merge($item[$field], $fnames);
		}

		return $fnames;
	}

	private function sizers (stdClass $settings, $file, string $folder, string $fname)
	{
		if(property_exists($settings, 'sizers') == false) {
			return ;
		}
		foreach ($settings->sizers as $size => $set) {
			$sizeFolder = $this->checkDir("{$folder}{$size}/");
			$upload = "{$sizeFolder}{$fname}";
			$image = Image::make($file);
			if(!is_null($set[0])) {
				$image->widen($set[0]);
			}
			else {
				$image->heighten($set[1]);
			}
			$image->save($upload, $set[2]);
		}
	}

	private function generateName (string $folder, int $name = null, string $extension, string $lang = null): string
	{
		if($lang) {
            $lang .= '-';
        }

		// Once file
		if(!is_null($name)) {
			return "{$lang}{$name}.{$extension}";
		}

		// Multiple files
		do {
			$name = strtolower(str_random(10));
			$fname = "{$lang}{$name}.{$extension}";
		}
		while(File::exists("{$folder}{$fname}"));

		return $fname;
	}

	public function drop ($item, string $field = null): void
	{
		$src = [];
		$settings = $this->settings($item);
		foreach ($settings as $key => $set) {
			// UPDATE (only changed items)
			if(!is_null($field) && $key != $field) {
				continue;
			}
			$folder = str_replace('public/', '', $set->folder);
			$src[] = public_path($folder.$item[$key]);
			if(!property_exists($set, 'sizers')) {
				continue;
			}

			$sizers = array_keys($set->sizers);
			foreach ($sizers as $size) {
				$src[] = public_path($folder.$size.'/'.$item[$key]);
			}
		}
		File::delete($src);
	}

	private function slice ($item, string $field, int $pos): void
	{
		$files = call_user_func([ $item, $field ]);
		File::delete(public_path($files[$pos]));

		$data = $item[$field];
		array_splice($data, $pos, 1);
		$item[$field] = $data;
		$item->save();
	}

	private function shuffle ($item, string $field, array $order): void
	{
		$files = $item[$field];
		$data = [];
		foreach ($order as $i) {
			$data[] = $files[$i];
		}
		$item[$field] = $data;
		$item->save();
	}

	private function settings ($item): array
	{
		$settings = $item::FILES;
		$form = config("admin.{$item->table}.form");
		foreach ($settings as $field => $folder) {
			$set = $form[$field];
			unset($set['type'], $set['signature']);
			$settings[$field] = (object) array_merge(
				[ 'folder' => $folder ],
				$set
			);
		}
		return $settings;
	}

	private function checkDir ($dir): string
	{
		$dir = base_path($dir);
		if(!File::exists($dir)) {
			File::makeDirectory($dir);
		}
		return $dir;
	}
}