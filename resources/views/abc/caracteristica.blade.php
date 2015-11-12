@extends('app')

@section('title')
	ABC Caracteristica
@stop

@section('header')
	<h2>ABC Caracteristica</h2>
  <hr>
@stop

@section('info')
	<p></p>
@stop

@section('body')
<h3>Creacion de Caracteristica</h3>
<hr>
	{!! Form::open(['url' => 'a_caracteristica']) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Duracion:') !!}
		{!! Form::text('duracion') !!}
		{!! Form::submit('Crear') !!}
	{!! Form::close() !!}


	<h3>Eliminacion de Caracteristica</h3>
	<hr>

	{!! Form::open(['url' => 'b_caracteristica']) !!}
		{!! Form::label('Caracteristica a borrar:') !!}
		{!! Form::select('id',$opciones, array_values($opciones)[0]) !!}
		{!! Form::submit('Borrar') !!}
	{!! Form::close() !!}

	<h3>Actualizacion de Caracteristica</h3>
	<hr>
	{!! Form::open(['url' => 'c_caracteristica']) !!}
		{!! Form::label('Característica a actualizar:') !!}
		{!! Form::select('id',$opciones, array_values($opciones)[0]) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Duracion:') !!}
		{!! Form::text('duracion') !!}
		{!! Form::submit('Actualizar') !!}
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
	<li><a href="/abc_usuario">Usuario</a></li>@stop
	<li><a href="/merge">Merge</a></li>
