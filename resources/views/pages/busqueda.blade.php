@extends('app')

@section('title')
	Busqueda
@stop

@section('header')
	<h2>Resultados de la busqueda</h2>	
@stop

@section('nav_items')
	<li class="current"><a href="#">Principal</a></li>
	<li><a href="/registro">Registro</a></li>
@stop

@section('body')
	{{ $resultados }}
@stop

