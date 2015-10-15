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
    				return 'super';
    			case 1:
    				return 'admin';
    			default:
    				return 'normal';
    		}
    	}else{
    		return view('pages.index')->with('error','Datos incorrectos');
    	}
    }

}
