@extends('app')

@section('title')
	ABC INDEX
@stop
@section('header')
	<h2>Manejo de ABC.</h2>
  <hr>
@stop
	@if (isset($error))
		{{ $error }}
	@endif

@section('nav_items')
	<li><a href="/">Principal</a></li>
	<li><a href="/abc_establecimiento">Establecimiento</a></li>
	<li><a href="/abc_tipo_establecimiento">Tipo Establecimiento</a></li>
	<li><a href="/abc_servicio">Servicio</a></li>
	<li><a href="/abc_tipo_servicio">Tipo Servicio</a></li>
	<li><a href="/abc_dimension">Dimension</a></li>
	<li><a href="/abc_categoria">Categoria</a></li>
	<li><a href="/abc_caracteristica">Caracteristica</a></li>
	<li><a href="/abc_usuario">Usuario</a></li>
@stop
