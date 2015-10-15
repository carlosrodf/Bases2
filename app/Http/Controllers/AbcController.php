<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class AbcController extends Controller
{
    //

    public function establecimiento(){
      return view('abc.establecimiento');
    }

    public function crearEstablecimiento(){
      $validacion = DB::select('select count(*) as N from ESTABLECIMIENTO where nombre = ?;',array(Request::get('nombre')));
      if($validacion[0]->N == 0){
          DB::statement('call crearEstablecimiento(?,?,?,0,?);',array(
              Request::get('nombre'),
              Request::get('posicion'),
              Request::get('descripcion'),
              Request::get('oficial')
          ));
          return redirect('/');
      }else{
          return view('abc.establecimiento');
      }
    }
}
