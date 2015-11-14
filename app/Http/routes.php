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

Route::post('/establecimiento/reservar','defaultController@reservar');
Route::get('/establecimiento/{id}','defaultController@verEstablecimiento');
Route::post('/establecimiento/{id}','defaultController@calificarEstablecimiento');

Route::get('/misReservas','defaultController@abc_reservas');
Route::post('/misReservas','defaultController@actualizarReserva');
Route::post('/eliminarReserva','defaultController@eliminarReserva');

Route::get('/abc_establecimiento','AbcController@establecimiento');
Route::post('/a_establecimiento','AbcController@crearEstablecimiento');
Route::post('/b_establecimiento','AbcController@borrarEstablecimiento');
Route::post('/c_establecimiento','AbcController@actualizarEstablecimiento');

Route::get('/abc_tipo_establecimiento','AbcController@tipoEstablecimiento');
Route::post('/a_tipo_establecimiento','AbcController@crearTipoEstablecimiento');
Route::post('/b_tipo_establecimiento','AbcController@borrarTipoEstablecimiento');
Route::post('/c_tipo_establecimiento','AbcController@actualizarTipoEstablecimiento');

Route::get('/abc_tipo_servicio','AbcController@tipoServicio');
Route::post('/a_tipo_servicio','AbcController@crearTipoServicio');
Route::post('/b_tipo_servicio','AbcController@borrarTipoServicio');
Route::post('/c_tipo_servicio','AbcController@actualizarTipoServicio');

Route::get('/abc_servicio','AbcController@servicio');
Route::post('/a_servicio','AbcController@crearServicio');
Route::post('/b_servicio','AbcController@borrarServicio');
Route::post('/c_servicio','AbcController@actualizarServicio');

Route::get('/abc_dimension','AbcController@dimension');
Route::post('/a_dimension','AbcController@crearDimension');
Route::post('/b_dimension','AbcController@borrarDimension');
Route::post('/c_dimension','AbcController@actualizarDimension');

Route::get('/abc_categoria','AbcController@categoria');
Route::post('/a_categoria','AbcController@crearCategoria');
Route::post('/b_categoria','AbcController@borrarCategoria');
Route::post('/c_categoria','AbcController@actualizarCategoria');

Route::get('/abc_caracteristica','AbcController@caracteristica');
Route::post('/a_caracteristiac','AbcController@crearCaracteristica');
Route::post('/b_caracteristica','AbcController@borrarCaracteristica');
Route::post('/c_caracteristica','AbcController@actualizarCaracteristica');

Route::get('/abc_usuario','AbcController@usuario');
Route::post('/a_usuario','AbcController@crearUsuario');
Route::post('/b_usuario','AbcController@borrarUsuario');
Route::post('/c_usuario','AbcController@actualizarUsuario');

Route::get('/abc_index','AbcController@index');

Route::get('/merge','AbcController@merge');
Route::post('/hacer_merge','AbcController@hacerMerge');
