<?php

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

Route::get('/', ['as' => 'login', 'uses' => 'BaseController@getLogin']);
Route::post('/', ['as' => 'login.post', 'uses' => 'BaseController@postLogin']);

Route::middleware(['authentication'])->group(function () {
    Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'DashboardController@getDashboard']);
    Route::get('/exit', ['as' => 'exit', 'uses' => 'BaseController@getExit']);
});
