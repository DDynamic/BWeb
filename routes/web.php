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

Route::get('/auth/login', ['as' => 'auth.login', 'uses' => 'BaseController@getLogin']);
Route::post('/auth/login', ['as' => 'auth.login.post', 'uses' => 'BaseController@postLogin']);

Route::middleware(['authentication'])->group(function () {
    Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@getDashboard']);
    Route::get('/auth/exit', ['as' => 'auth.exit', 'uses' => 'BaseController@getExit']);
});
