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

Route::get('/', ['as' => 'dashboard', 'uses' => 'DashboardController@index']);

Route::get('/auth/login', ['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('/auth/login', ['uses' => 'Auth\LoginController@login']);
Route::get('/auth/exit', ['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);
