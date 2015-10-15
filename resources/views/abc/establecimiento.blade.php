@extends('app')

@section('title')
	ABC Establecimiento
@stop

@section('header')
	<h2>Creacion de establecimiento</h2>
  <hr>
@stop

@section('info')
	<p></p>
@stop

@section('body')
	{!! Form::open(['url' => 'a_establecimiento']) !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Posicion:') !!}
		{!! Form::text('posicion') !!}
		{!! Form::label('Descripcion:') !!}
		{!! Form::text('descripcion') !!}
		{!! Form::label('Tipo:') !!}
		{!! Form::text('tipo') !!}
		{!! Form::label('Oficial:') !!}
		{!! Form::text('oficial') !!}
		{!! Form::submit('Crear') !!}
	{!! Form::close() !!}

	@if (isset($error))
		{{ $error }}
	@endif

@stop

@section('nav_items')
	<li><a href="/">Principal</a></li>
	<li class="current"><a href="#">Registro</a></li>
@stop
