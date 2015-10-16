@extends('app')

@section('title')
	ABC Servicio
@stop

@section('header')
	<h2>ABC Servicio</h2>
  <hr>
@stop

@section('info')
	<p></p>
@stop

@section('body')
<h3>Creacion de Servicio</h3>
<hr>
	{!! Form::open(['url' => 'a_servicio']) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Cupo:') !!}
		{!! Form::text('cupo') !!}
		{!! Form::label('Establecimiento:') !!}
		{!! Form::select('establecimiento',$opciones3, array_values($opciones3)[0]) !!}
		{!! Form::label('Tipo de Servicio:') !!}
		{!! Form::select('tipo_servicio',$opciones, array_values($opciones)[0]) !!}
		{!! Form::submit('Crear') !!}
	{!! Form::close() !!}


	<h3>Eliminacion de Servicio</h3>
	<hr>

	{!! Form::open(['url' => 'b_servicio']) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::select('id',$opciones2, array_values($opciones2)[0]) !!}
		{!! Form::submit('Borrar') !!}
	{!! Form::close() !!}

	<h3>Actualizacion de Servicio</h3>
	<hr>
	{!! Form::open(['url' => 'c_servicio']) !!}
		{!! Form::label('Servicio a actualizar:') !!}
		{!! Form::select('id',$opciones2, array_values($opciones2)[0]) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Cupo:') !!}
		{!! Form::text('cupo') !!}
		{!! Form::label('Establecimiento:') !!}
		{!! Form::select('establecimiento',$opciones3, array_values($opciones3)[0]) !!}
		{!! Form::label('Tipo de Servicio:') !!}
		{!! Form::select('tipo_servicio',$opciones, array_values($opciones)[0]) !!}
		{!! Form::label('Punteo:') !!}
		{!! Form::text('punteo') !!}
		{!! Form::submit('Actualizar') !!}
	{!! Form::close() !!}

	@if (isset($error))
		{{ $error }}
	@endif

@stop

@section('nav_items')
	<li><a href="/">Principal</a></li>
	<li class="current"><a href="#">Registro</a></li>
@stop
