@extends('app')

@section('title')
	ABC Tipo Servicio
@stop

@section('header')
	<h2>ABC Tipo Servicio</h2>
  <hr>
@stop

@section('info')
	<p></p>
@stop

@section('body')
<h3>Creacion de Tipo Servicio</h3>
<hr>
	{!! Form::open(['url' => 'a_tipo_servicio']) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Descripcion:') !!}
		{!! Form::text('descripcion') !!}
		{!! Form::submit('Crear') !!}
	{!! Form::close() !!}


	<h3>Eliminacion de Tipo Servicio</h3>
	<hr>

	{!! Form::open(['url' => 'b_tipo_servicio']) !!}
		{!! Form::label('Tipo Servicio a borrar:') !!}
		{!! Form::select('id',$opciones, array_values($opciones)[0]) !!}
		{!! Form::submit('Borrar') !!}
	{!! Form::close() !!}

	<h3>Actualizacion de Tipo Servicio</h3>
	<hr>
	{!! Form::open(['url' => 'c_tipo_servicio']) !!}
		{!! Form::label('Tipo Servicio a actualizar:') !!}
		{!! Form::select('id',$opciones, array_values($opciones)[0]) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Descripción:') !!}
		{!! Form::text('descripcion') !!}
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
	<li><a href="/abc_usuario">Usuario</a></li>
	<li><a href="/merge">Merge</a></li>
@stop
