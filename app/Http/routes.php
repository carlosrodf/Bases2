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

Route::get('/registro','defaultController@registro');
Route::post('/registro','defaultController@registrar');

Route::get('/busqueda','defaultController@buscar');
Route::post('/busqueda','defaultController@busqueda');

Route::get('/establecimiento/{id}','defaultController@verEstablecimiento');
Route::post('/establecimiento/{id}','defaultController@calificarEstablecimiento');

Route::get('/abc_establecimiento','AbcController@establecimiento');
Route::post('/a_establecimiento','AbcController@crearEstablecimiento');
Route::post('/b_establecimiento','AbcController@borrarEstablecimiento');
Route::post('/c_establecimiento','AbcController@actualizarEstablecimiento');
