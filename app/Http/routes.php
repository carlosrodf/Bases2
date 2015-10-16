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

Route::get('/abc_establecimiento','AbcController@establecimiento');
Route::post('/a_establecimiento','AbcController@crearEstablecimiento');
Route::post('/b_establecimiento','AbcController@borrarEstablecimiento');
Route::post('/c_establecimiento','AbcController@actualizarEstablecimiento');

Route::get('/abc_tipo_establecimiento','AbcController@tipoEstablecimiento');
Route::post('/a_tipo_establecimiento','AbcController@crearTipoEstablecimiento');
Route::post('/b_tipo_establecimiento','AbcController@borrarTipoEstablecimiento');
Route::post('/c_tipo_establecimiento','AbcController@actualizarTipoEstablecimiento');

Route::get('/abc_index','AbcController@index');
