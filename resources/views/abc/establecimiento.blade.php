@extends('app')

@section('title')
	ABC Establecimiento
@stop

@section('header')
	<h2>ABC Establecimiento</h2>
  <hr>
@stop

@section('info')
	<p></p>
@stop

@section('body')
<h3>Creacion de Establecimiento</h3>
<hr>
	{!! Form::open(['url' => 'a_establecimiento']) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Posicion:') !!}
		{!! Form::text('posicion') !!}
		{!! Form::label('Descripcion:') !!}
		{!! Form::text('descripcion') !!}
		{!! Form::label('Tipo:') !!}
		{!! Form::select('tipo',$opciones, array_values($opciones)[0]) !!}
		{!! Form::label('Oficial:') !!}
		{!! Form::checkbox('oficial', '1') !!}
		{!! Form::submit('Crear') !!}
	{!! Form::close() !!}


	<h3>Eliminacion de Establecimiento</h3>
	<hr>

	{!! Form::open(['url' => 'b_establecimiento']) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::select('id',$opciones2, array_values($opciones2)[0]) !!}
		{!! Form::submit('Borrar') !!}
	{!! Form::close() !!}

	<h3>Actualizacion de Establecimiento</h3>
	<hr>
	{!! Form::open(['url' => 'c_establecimiento']) !!}
		{!! Form::label('Establecimiento a actualizar:') !!}
		{!! Form::select('id',$opciones2, array_values($opciones2)[0]) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Posicion:') !!}
		{!! Form::text('posicion') !!}
		{!! Form::label('Descripcion:') !!}
		{!! Form::text('descripcion') !!}
		{!! Form::label('Punteo:') !!}
		{!! Form::text('punteo') !!}
		{!! Form::label('Tipo:') !!}
		{!! Form::select('tipo',$opciones, array_values($opciones)[0]) !!}
		{!! Form::label('Oficial:') !!}
		{!! Form::checkbox('oficial', '1') !!}
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
