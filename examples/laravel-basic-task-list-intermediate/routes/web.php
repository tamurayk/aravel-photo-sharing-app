<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/tasks', 'TaskController@index')->name('task.index')->middleware('auth');
Route::post('/task', 'TaskController@store')->name('task.store')->middleware('auth');
Route::delete('/task/{task}', 'TaskController@destroy')->name('task.destroy')->middleware('auth');
