<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use Auth;
use Route;
use File;

abstract class AdminController extends Controller
{
	public $page = [];
	public $menu = [];
	protected $model;
	protected $form;
	protected $rules;

	public function __construct ()
	{
		$this->createMenu();
	}

	abstract protected function checkDrop ($item): bool;

	/*
	Set model
	*/
	protected function setModel ($model): void
	{
		$this->model = $model;
	}

	/*
	Set form for create and edit
	Set form template from config('[ROUTE].form')
	/config/admin/[ROUTE-2nd].php
	*/
	protected function setForm (string $line = null, $values = null): void
	{
		if(is_null($line) && is_null($values)) {
			$this->form = config("{$this->page['route']}.form", []);
			return ;
		}

		list($field, $property) = explode('.', $line);
		$this->form[$field][$property] = $values;
	}


	/*
	Set validate rules for store and update
	Set rules from config('[ROUTE].rules')
	/config/admin/[ROUTE-2nd].php
	*/
	protected function setRules (): void
	{
		$this->rules = config("{$this->page['route']}.rules", []);
	}

	/*
	Set rule for dynamic parameters
	*/
	protected function setRule (string $field, string $update = null): void
	{
		if(!preg_match('/\./', $field)) {
			$this->rules[$field] = $update;
			return ;
		}
		self::setDefinitionRule($field, $update);
	}

	/*
	Set current page route
	Set tag Title
	Set tag H1
	Set available functions
	*/
	protected function setPage (array $settings): void
	{
		$this->page = $settings;
		if($settings['route'] == 'admin.index' || array_key_exists('func', $settings)) {
			return ;
		}

		$this->page['func'] = [
			'create',
			'edit',
			'delete'
		];
	}

	/*
	Get page and set variables
	Set admin user
	Set menu from config('admin._menu')
	/config/admin/_menu.php
	*/
	protected function getPage ()
	{
		$settings = [
			'admin' => Auth::user(),
			'menu' => $this->menu,
		];
		$this->page = array_merge($settings, $this->page);
		return (object) $this->page;
	}

	protected function setH1 (string $h1, bool $full = false): void
	{
		if($full) {
			$this->page['h1'] = $h1;
		}
		else {
			$this->page['h1'] .= $h1;
		}
	}

	protected function setCurrent (string $func): void
	{
		$this->page['current'] = $func;

		if($func == 'create') {
			$this->setH1(' :создание');
		}
		if($func == 'edit') {
			$this->setH1(' :редактирование');
		}
	}

	/*
	Get generated form tamplate from config
	*/
	protected function getForm (): array
	{
		$fields = $this->form;

		foreach ($fields as $key => $field) {
			// Css
			if(!array_key_exists('class', $field)) {
				$fields[$key]['class'] = 12;
			}
			// Required
			if(!array_key_exists('required', $field)) {
				$fields[$key]['required'] = false;
			}
			// Signature
			if(!array_key_exists('signature', $field)) {
				$fields[$key]['signature'] = null;
			}
			// Disable label
			if(isset($field['type']) &&  (
				$field['type'] == 'wysiwyg' ||
				$field['type'] == 'files' ||
				$field['type'] == 'include'
                )) {
				$fields[$key]['line'] = true;
			}
			// Files mimes
			if(isset($field['type']) && ($field['type'] == 'files' && !array_key_exists('mimes', $field))) {
				$regex = '/(?:.*)mimetypes:([^\|]+)(?:.*)/';
				$mimes = preg_replace($regex, '$1', $this->rules[$key]);
				$fields[$key]['mimes'] = $mimes;
			}
			// Default media folder
			if(isset($field['type']) && (
				$field['type'] == 'wysiwyg' &&
				!array_key_exists('media', $field)
                )) {
				$fields[$key]['media'] = 'img/media/';
			}
			// Set select's items
			if(isset($field['type']) && (
				$field['type'] == 'select' &&
				is_string($field['items'])
                )) {
				$fields[$key]['items'] = $field['items']::select();
			}

			$fields[$key] = (object) $fields[$key];
		}
		$this->form = $fields;

		return $this->form;
	}

	protected function getFiles ($item, string $field): array
	{
		$files = [];
		$folder = $item::FILES[$field];
		$value = $item[$field];
		if(is_string($value)) {
			$files = [
				call_user_func([ $item, $field ])
			];
		}
		foreach ($files as $key => $file) {
			if(!File::exists($file)) {
				unset($files[$key]);
				continue;
			}
			$mime = mime_content_type($file);
			$files[$key] = (object) [
				'src'   => $file,
				'mime'  => $mime,
				'image' => strpos($mime, 'image') !== false,
			];
		}
		return $files;
	}

	public function destroy (int $id, Request $request)
	{
		$item = $this->model::findOrFail($id);
		$check = $this->checkDrop($item);
		if($check) {
			$item->delete();
			if($request->ajax()) {
				$response = 'Удалено. ID: <b>'. $item->id .'</b>';
				return response()->json($response);
			}

			return redirect()
			->route("{$this->page['route']}.index")
			->with('alert', "Удалено. ID: <b>{$item->id}</b>");
		}

		return $this->errorDrop($request);
	}

 	private function createMenu (): void
 	{
        $lang = App::getLocale();

 		$links = config('admin._menu');
		foreach ($links as $key => $link) {
			$link['route'] = "admin.{$key}.index";

            if($lang != 'ru') {
                $link['title'] = $link['en']['title'];
            }

			if(!Route::has($link['route'])) {
				continue;
			}
			if(array_key_exists(0, $link)) {
				foreach($link[0] as $key2 => $link2) {
					$link2 = (object) $link2;
					$link2->route = "admin.{$key2}.index";
					if(!Route::has($link2->route)) {
						continue;
					}
					$link['drop'][$key2] = $link2;
				}
				unset($link[0]);
			}
			if(array_key_exists('count', $link)) {
				$count = $link['count'];
				$q = $count[0]::query();
				foreach ($count as $ci => $condition) {
					if($ci == 0) {
						continue;
					}
					$q->where($condition[0], $condition[1]);
				}
				$link['count'] = $q->count();
			}
			$link = (object) $link;

			$this->menu[$key] = $link;
		}
	}

	private function setDefinitionRule (string $field, string $update = null): void
	{
		list($field, $key) = explode('.', $field);
		$lines = explode('|', $this->rules[$field]);
		$rules = [];
		foreach ($lines as $i => $value) {
			$line = explode(':', $value);
			$rule = $line[0];
			$set = count($line) == 1 ? null : $line[1];

			if($rule == $key) {
				if(is_null($update)) {
					continue;
				}
				elseif(is_null($set)) {
					$rule = $update;
				}
				else {
					$set = $update;
				}
			}
			$rules[$rule] = $set;
		}

		$current = [];
		foreach ($rules as $key => $value) {
			$current[] = is_null($value) ? $key : "{$key}:{$value}";
		}

		$this->rules[$field] = implode('|', $current);
	}
}
