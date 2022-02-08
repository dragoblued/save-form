<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Renderable;

use App\Models\Feedback;

class FeedbackController extends AdminController
{
	protected $model;

	public function __construct ()
	{
		parent::__construct();

		$this->setPage([
			'route' => 'admin.feedback',
			'title' => 'Feedback - Acruxcs [ ADMIN ]',
			'h1'    => 'Feedback'
		]);
		$this->setModel(Feedback::class);
		$this->setForm();
		$this->setRules();
	}

	public function index (Request $request): Renderable
	{
		$items = $this->model::orderBy('created_at', 'desc')
		->paginate(20);

		$data = [
			'page'  => $this->getPage(),
			'items' => $items,
		];
		return view('admin._list', $data);
	}

	public function create (): Renderable
	{
		$this->setCurrent('create');

		$data = [
			'page' => $this->getPage(),
			'form' => $this->getForm(),
		];
		return view('admin._form', $data);
	}

	public function store (Request $request): RedirectResponse
	{
		$request->validate($this->rules);

		$item = $this->model::create($request->all());

		return redirect()
		->route("{$this->page['route']}.index")
		->with('alert', "Добавлено. ID: <b>{$item->id}</b>");
	}

	public function edit ($id): Renderable
	{
		$this->setCurrent('edit');

		$item = $this->model::findOrFail($id);

		$data = [
			'page' => $this->getPage(),
			'form' => $this->getForm(),
			'item' => $item,
		];
		return view('admin._form', $data);
	}

	public function update ($id, Request $request): RedirectResponse
	{
		$request->validate($this->rules);

		$item = $this->model::findOrFail($id);
		$item->fill($request->all());
		$item->save();

		return redirect()
		->route("{$this->page['route']}.index")
		->with('alert', "Отредактировано. ID: <b>{$item->id}</b>");
	}

	protected function checkDrop ($item): bool {
		return true;
	}
}
