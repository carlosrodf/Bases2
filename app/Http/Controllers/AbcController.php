<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AbcController extends Controller
{
    //
    public function index(){
      return view('abc.abc_index');
    }

    //MERGE

    public function merge(){
      $establecimientos = DB::select('call getEstablecimientosOficiales()');
      $opciones = array();
      foreach ($establecimientos as $establecimiento) {
        $opciones[$establecimiento->establecimiento] = $establecimiento->nombre;
      }

      $establecimientos = DB::select('call getEstablecimientosNoOficiales()');
      $opciones2 = array();
      foreach ($establecimientos as $establecimiento) {
        $opciones2[$establecimiento->establecimiento] = $establecimiento->nombre;
      }

      return view('abc.merge',array('opciones' => $opciones, 'opciones2' => $opciones2));
    }     

    public function hacerMerge(){
          DB::statement('call merge(?,?);',array(
              Request::get('oficial'),
              Request::get('no_oficial')
          ));
          return redirect('/abc_index');
    }

    //ABC ESTABLECIMIENTO

    public function establecimiento(){
      $tipos_establecimiento = DB::select('call getTiposEstablecimiento()');
      $opciones = array();
      foreach ($tipos_establecimiento as $tipo_establecimiento) {
        $opciones[$tipo_establecimiento->tipo_establecimiento] = $tipo_establecimiento->nombre;
      }

      $establecimientos = DB::select('call getEstablecimientos()');
      $opciones2 = array();
      foreach ($establecimientos as $establecimiento) {
        $opciones2[$establecimiento->establecimiento] = $establecimiento->nombre;
      }

      return view('abc.establecimiento',array('opciones' => $opciones, 'opciones2' => $opciones2));
    }

    public function crearEstablecimiento(){
      $validacion = DB::select('select count(*) as N from ESTABLECIMIENTO where nombre = ?;',array(Request::get('nombre')));
      if($validacion[0]->N == 0){
          $oficial = 0;
          if(Request::get('oficial')==1) $oficial = 1;
          DB::statement('call crearEstablecimiento(?,?,?,?,?);',array(
              Request::get('nombre'),
              Request::get('posicion'),
              Request::get('descripcion'),
              Request::get('tipo'),
              $oficial
          ));
          return redirect('/abc_index');
      }else{
          return view('abc.abc_establecimiento');
      }
    }

    public function borrarEstablecimiento(){
      DB::statement('call eliminarEstablecimiento(?);', array(
        Request::get('id')
      ));
      return redirect('/abc_index');
    }

    public function actualizarEstablecimiento(){
          $oficial = 0;
          if(Request::get('oficial')==1) $oficial = 1;
          DB::statement('call actualizarEstablecimiento(?,?,?,?,?,?,?);',array(
              Request::get('id'),
              Request::get('nombre'),
              Request::get('posicion'),
              Request::get('descripcion'),
              Request::get('punteo'),
              Request::get('tipo'),
              $oficial
          ));
          return redirect('/abc_index');

    }


    //ABC TIPO_ESTABLECIMIENTO

    public function tipoEstablecimiento(){
      $tipos_establecimiento = DB::select('call getTiposEstablecimiento()');
      $opciones = array();
      foreach ($tipos_establecimiento as $tipo_establecimiento) {
        $opciones[$tipo_establecimiento->tipo_establecimiento] = $tipo_establecimiento->nombre;
      }

      return view('abc.tipo_establecimiento',array('opciones' => $opciones));
    }

    public function crearTipoEstablecimiento(){
      $validacion = DB::select('select count(*) as N from TIPO_ESTABLECIMIENTO where nombre = ?;',array(Request::get('nombre')));
      if($validacion[0]->N == 0){
          DB::statement('call crearTipoEstablecimiento(?,?);',array(
              Request::get('nombre'),
              Request::get('descripcion')
          ));
          return redirect('/abc_index');
      }else{
          return view('abc.abc_tipo_establecimiento');
      }
    }

    public function borrarTipoEstablecimiento(){
      DB::statement('call eliminarTipoEstablecimiento(?);', array(
        Request::get('id')
      ));
      return redirect('/abc_index');
    }

    public function actualizarTipoEstablecimiento(){
          DB::statement('call actualizarTipoEstablecimiento(?,?);',array(
              Request::get('id'),
              Request::get('nombre')
          ));
          return redirect('/abc_index');
    }

      //ABC TIPO_SERVICIO

    public function tipoServicio(){
      $tipos_servicio = DB::select('call getTiposServicio()');
      $opciones = array();
      foreach ($tipos_servicio as $tipo_servicio) {
        $opciones[$tipo_servicio->tipo_servicio] = $tipo_servicio->nombre;
      }

      return view('abc.tipo_servicio',array('opciones' => $opciones));
    }

    public function crearTipoServicio(){
      $validacion = DB::select('select count(*) as N from TIPO_ESTABLECIMIENTO where nombre = ?;',array(Request::get('nombre')));
      if($validacion[0]->N == 0){
          DB::statement('call crearTipoServicio(?,?);',array(
              Request::get('nombre'),
              Request::get('descripcion')
          ));
          return redirect('/abc_index');
      }else{
          return view('abc.abc_tipo_servicio');
      }
    }

    public function borrarTipoServicio(){
      DB::statement('call eliminarTipoServicio(?);', array(
        Request::get('id')
      ));
      return redirect('/abc_index');
    }

    public function actualizarTipoServicio(){
          DB::statement('call actualizarTipoServicio(?,?,?);',array(
              Request::get('id'),
              Request::get('nombre'),
              Request::get('descripcion')
          ));
          return redirect('/abc_index');
    }


      //ABC SERVICIO

        public function servicio(){
      $tipos_servicio = DB::select('call getTiposServicio()');
      $opciones = array();
      foreach ($tipos_servicio as $tipo_servicio) {
        $opciones[$tipo_servicio->tipo_servicio] = $tipo_servicio->nombre;
      }

      $servicios = DB::select('call getServicios()');
      $opciones2 = array();
      foreach ($servicios as $servicio) {
        $opciones2[$servicio->servicio] = $servicio->nombre;
      }

      $establecimientos = DB::select('call getEstablecimientos()');
      $opciones3 = array();
      foreach ($establecimientos as $establecimiento) {
        $opciones3[$establecimiento->establecimiento] = $establecimiento->nombre;
      }

      return view('abc.servicio',array('opciones' => $opciones, 'opciones2' => $opciones2, 'opciones3' => $opciones3));
    }

    public function crearServicio(){
      $validacion = DB::select('select count(*) as N from SERVICIO where nombre = ?;',array(Request::get('nombre')));
      if($validacion[0]->N == 0){
          DB::statement('call crearServicio(?,?,?,?);',array(
              Request::get('nombre'),
              Request::get('cupo'),
              Request::get('establecimiento'),
              Request::get('tipo_servicio')
          ));
          return redirect('/abc_index');
      }else{
          return view('abc.abc_servicio');
      }
    }

    public function borrarServicio(){
      DB::statement('call eliminarServicio(?);', array(
        Request::get('id')
      ));
      return redirect('/abc_index');
    }

    public function actualizarServicio(){
          DB::statement('call actualizarEstablecimiento(?,?,?,?,?,?);',array(
              Request::get('id'),
              Request::get('nombre'),
              Request::get('cupo'),
              Request::get('establecimiento'),
              Request::get('tipo_servicio'),
              Request::get('punteo')
          ));
          return redirect('/abc_index');

    }

    //ABC DIMENSION

    public function dimension(){
      $dimensiones = DB::select('call getDimensiones()');
      $opciones = array();
      foreach ($dimensiones as $dimension) {
        $opciones[$dimension->dimension] = $dimension->nombre;
      }

      return view('abc.dimension',array('opciones' => $opciones));
    }

    public function crearDimension(){
      $validacion = DB::select('select count(*) as N from DIMENSION where nombre = ?;',array(Request::get('nombre')));
      if($validacion[0]->N == 0){
          DB::statement('call crearDimension(?);',array(
              Request::get('nombre')
          ));
          return redirect('/abc_index');
      }else{
          return view('abc.abc_dimension');
      }
    }

    public function borraDimension(){
      DB::statement('call eliminarDimension(?);', array(
        Request::get('id')
      ));
      return redirect('/abc_index');
    }

    public function actualizarDimension(){
          DB::statement('call actualizarDimension(?,?);',array(
              Request::get('id'),
              Request::get('nombre')
          ));
          return redirect('/abc_index');
    }


//ABC CATEGORIA

    public function categoria(){
      $dimensiones = DB::select('call getDimensiones()');
      $opciones = array();
      foreach ($dimensiones as $dimension) {
        $opciones[$dimension->dimension] = $dimension->nombre;
      }

      $categorias = DB::select('call getCategorias()');
      $opciones2 = array();
      foreach ($categorias as $categoria) {
        $opciones2[$categoria->categoria] = $categoria->nombre;
      }

      return view('abc.categoria',array('opciones' => $opciones, 'opciones2' => $opciones2));
    }

    public function crearCategoria(){
      $validacion = DB::select('select count(*) as N from CATEGORIA where nombre = ?;',array(Request::get('nombre')));
      if($validacion[0]->N == 0){
          DB::statement('call crearCategoria(?,?);',array(
              Request::get('dimension'),
              Request::get('nombre')
          ));
          return redirect('/abc_index');
      }else{
          return view('abc.abc_categoria');
      }
    }

    public function borrarCategoria(){
      DB::statement('call eliminarCategoria(?);', array(
        Request::get('id')
      ));
      return redirect('/abc_index');
    }

    public function actualizarCategoria(){
          DB::statement('call actualizarCategoria(?,?);',array(
              Request::get('id'),
              Request::get('nombre')
          ));
          return redirect('/abc_index');

    }

    //ABC CARACTERISTICA

    public function caracteristica(){
      $caracteristicas = DB::select('call getCaracteristicas()');
      $opciones = array();
      foreach ($caracteristicas as $caracteristica) {
        $opciones[$caracteristica->caracteristica] = $caracteristica->nombre;
      }

      return view('abc.caracteristica',array('opciones' => $opciones));
    }

    public function crearCaracteristica(){
      $validacion = DB::select('select count(*) as N from CARACTERISTICA where nombre = ?;',array(Request::get('nombre')));
      if($validacion[0]->N == 0){
          DB::statement('call crearCaracteristica(?);',array(
              Request::get('nombre'),
              Request::get('duracion')
          ));
          return redirect('/abc_index');
      }else{
          return view('abc.abc_caracteristica');
      }
    }

    public function borraCaracteristica(){
      DB::statement('call eliminarCaracteristica(?);', array(
        Request::get('id')
      ));
      return redirect('/abc_index');
    }

    public function actualizarCaracteristica(){
          DB::statement('call actualizarCaracteristica(?,?,?);',array(
              Request::get('id'),
              Request::get('nombre'),
              Request::get('duracion')
          ));
          return redirect('/abc_index');
    }


//ABC USUARIO

    public function usuario(){
      $usuarios = DB::select('call getUsuarios()');
      $opciones = array();
      foreach ($usuarios as $usuario) {
        $opciones[$usuario->usuario] = $usuario->usuario;
      }

      return view('abc.usuario',array('opciones' => $opciones));
    }

    public function crearUsuario(){
      $validacion = DB::select('select count(*) as N from USUARIO where usuario = ?;',array(Request::get('usuario')));
      if($validacion[0]->N == 0){
          DB::statement('call crearUsuario(?,?,?,?,?);',array(
              Request::get('usuario'),
              Request::get('password'),
              Request::get('nombre'),
              Request::get('apellido'),
              Request::get('rol')
          ));
          return redirect('/abc_index');
      }else{
          return view('abc.abc_usuario');
      }
    }

    public function borrarUsuario(){
      DB::statement('call eliminarUsuario(?);', array(
        Request::get('usuario')
      ));
      return redirect('/abc_index');
    }

    public function actualizarUsuario(){
          DB::statement('call actualizarUsuario(?,?,?,?,?);',array(
              Request::get('usuario'),
              Request::get('password'),
              Request::get('nombre'),
              Request::get('apellido'),
              Request::get('rol')
          ));
          return redirect('/abc_index');
    }
}
