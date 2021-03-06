<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\{StatusController, HomeController, TaskController, LabelController};

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

Route::get('/', function (): View {
    return view('home.index');
})->name('index');

Auth::routes();

Route::resource('task_statuses', StatusController::class)->except(['show']);

Route::resource('tasks', TaskController::class);

Route::resource('labels', LabelController::class)->except(['show']);
