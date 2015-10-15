@extends('app')

@section('title')
	Establecimiento
@stop

@section('header')
	<h2>{{ $datos[0]->nombre}}</h2>	
	<p>{{ $datos[0]->descripcion}}</p>
@stop

@section('info')
@stop

@section('nav_items')
@stop

@section('body')
	<p>Detalles y mamadas</p>
@stop
<a href="http://www.w3schools.com">Visit W3Schools.com!</a>