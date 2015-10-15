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
    		return view('pages.userProfile');
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

}
