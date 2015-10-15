<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'defaultController@index');
Route::post('/', 'defaultController@login');

Route::get('/super','defaultController@super');
Route::get('/admin','defaultController@admin');
Route::get('/user','defaultController@user');
