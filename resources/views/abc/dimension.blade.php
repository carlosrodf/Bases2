@extends('app')

@section('title')
	ABC Dimension
@stop

@section('header')
	<h2>ABC Dimension</h2>
  <hr>
@stop

@section('info')
	<p></p>
@stop

@section('body')
<h3>Creacion de Dimension</h3>
<hr>
	{!! Form::open(['url' => 'a_dimension']) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::submit('Crear') !!}
	{!! Form::close() !!}


	<h3>Eliminacion de Dimension</h3>
	<hr>

	{!! Form::open(['url' => 'b_dimension']) !!}
		{!! Form::label('Dimension a borrar:') !!}
		{!! Form::select('id',$opciones, array_values($opciones)[0]) !!}
		{!! Form::submit('Borrar') !!}
	{!! Form::close() !!}

	<h3>Actualizacion de Dimension</h3>
	<hr>
	{!! Form::open(['url' => 'c_dimension']) !!}
		{!! Form::label('Dimension a actualizar:') !!}
		{!! Form::select('id',$opciones, array_values($opciones)[0]) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
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
