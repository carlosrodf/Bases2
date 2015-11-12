@extends('app')

@section('title')
	Merge de Establecimientos
@stop

@section('header')
	<h2>Merge de Establecimientos</h2>
  <hr>
@stop

@section('info')
	<p></p>
@stop

@section('body')
<hr>
	{!! Form::open(['url' => 'hacer_merge']) !!}
		{!! Form::label('Establecimiento Oficial:') !!}
		{!! Form::select('oficial',$opciones, array_values($opciones)[0]) !!}
		{!! Form::label('Establecimiento No Oficial:') !!}
		{!! Form::select('no_oficial',$opciones2, array_values($opciones2)[0]) !!}
		{!! Form::submit('Merge') !!}
	{!! Form::close() !!}


	@if (isset($error))
		{{ $error }}
	@endif

@stop

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
	<li><a href="/merge">Merge</a></li>
	@stop
