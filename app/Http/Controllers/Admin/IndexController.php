<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;
use Route;

class IndexController extends AdminController
{
    public function index (Request $request): Renderable
    {
        $this->setPage([
            'route' => 'admin.index',
            'title' => 'ADMIN: [AcruxCS]',
            'h1'    => 'Admin panel'
        ]);
        $configs = $this->pullConfigErrors();
        $data = [
            'page'    => $this->getPage(),
            'configs' => $configs,
        ];
        return view('admin.index', $data);
    }
    private function pullConfigErrors (): array
    {
        $errors = [];
        $links = config('admin._menu');
        foreach ($links as $key => $link) {
            if(array_key_exists(0, $link)) {
                $drop = $link[0];
                unset($links[$key][0]);
                $links = array_merge($links, $drop);
            }
        }
        foreach ($links as $key => $link) {
            // Route exists
            $route = "admin.{$key}.index";
            if(!Route::has($route)) {
                $errors[] = "Route <b>[{$route}]</b> not defined";
            }
            // View exists
            $view = "admin.{$key}";
            if(!view()->exists($view)) {
                $errors[] = "View <b>[{$view}]</b> not found";
            }
            // Config exists
            $config = "admin.{$key}";
            if(!config()->has($config)) {
                $errors[] = "Config <b>[{$config}]</b> not found";
            }
            else {
                $form = "{$config}.form";
                if(!config()->has($form)) {
                    $errors[] = "Config <b>[{$form}]</b> template not defined";
                }
                $rules = "{$config}.rules";
                if(!config()->has($rules)) {
                    $errors[] = "Config <b>[{$rules}]</b> validate not defined";
                }
            }
        }
        return $errors;
    }
    protected function checkDrop ($item): bool {
        return true;
    }
}
