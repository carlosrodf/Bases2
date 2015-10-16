<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use DB;

class defaultController extends Controller
{
    public function index(){
    	return view('pages.index');
    }

    public function super(){
    	if(Request::session()->get('rol',-1) == 0 and Request::session()->get('user','') !== ''){
    		return view('pages.superProfile');
    	}else{
    		return redirect('/');
    	}
    }

    public function admin(){
    	if(Request::session()->get('rol',-1) == 1 and Request::session()->get('user','') !== ''){
    		return view('pages.adminProfile');
    	}else{
    		return redirect('/');
    	}
    }

    public function user(){
    	if(Request::session()->get('rol',-1) == 2 and Request::session()->get('user','') !== ''){
    		return view('pages.normalProfile');
    	}else{
    		return redirect('/');
    	}
    }

    public function login(){
    	$user = Request::get('user');
    	$password = Request::get('password');
    	$output = DB::select('select nombre,apellido,rol from USUARIO where usuario = ? and password = ?;',array($user,$password));

    	if (count($output) == 1){

    		Request::session()->put('user',$user);
    		Request::session()->put('nombre',$output[0]->nombre);
    		Request::session()->put('apellido',$output[0]->apellido);
    		Request::session()->put('rol',$output[0]->rol);

    		switch ($output[0]->rol) {
    			case 0:
    				return redirect('super');
    			case 1:
    				return redirect('admin');
    			default:
    				return redirect('user');
    		}
    	}else{
    		return view('pages.index')->with('error','Datos incorrectos');
    	}
    }

    public function registro(){
        return view('pages.registro');
    }

    public function registrar(){
        $validacion = DB::select('select count(*) as N from USUARIO where usuario = ?;',array(Request::get('usuario')));
        if($validacion[0]->N == 0){
            DB::statement('call crearUsuario(?,?,?,?,2);',array(
                Request::get('usuario'),
                Request::get('password1'),
                Request::get('name'),
                Request::get('last')
            ));
            return redirect('/');
        }else{
            return view('pages.registro')->with('error','Datos incorrectos');
        }
    }

    public function busqueda(){
        $resultados = DB::select('call busqueda(?);',array(Request::get('search')));

        return view('pages.busqueda')->with('resultados',$resultados);
    }

    public function buscar(){
        return view('pages.busqueda');
    }

    public function verEstablecimiento($id){
        if(Request::session()->get('rol',-1) >= 0 and Request::session()->get('user','') !== ''){
            $datos = DB::select('select * from ESTABLECIMIENTO where establecimiento = ?;',array($id));
            $servicios = DB::select('select * from SERVICIO where establecimiento = ?',array($id));
            return view('pages.establecimiento',compact('datos','servicios'));
        }else{
            return redirect('/');
        }
    }

    public function calificarEstablecimiento($id){
        $servicio = Request::get('id_servicio',-1);
        DB::statement('call crearCalificacion(?,null,?,?)',array(
            Request::get('esferas',0),
            Request::session()->get('user',-1),
            $servicio
        ));
        return redirect('/establecimiento/'.$id);
    }

}
