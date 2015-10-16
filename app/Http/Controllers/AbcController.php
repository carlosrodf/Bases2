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
          return view('abc.abc_index');
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
          return view('abc.abc_index');
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



}
