<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\IndexController;
use App\Http\Controllers\Admin\FeedbackController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();
Route::any(
	'register',
	function () {
		return abort(404);
	}
);
Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::group([
    'prefix' => 'admin',
	'middleware' => 'auth',
	'as'         => 'admin.'
], function() {
	Route::resource('feedback', FeedbackController::class);
	Route::get('/', [IndexController::class, 'index'])->name('index');
});
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
