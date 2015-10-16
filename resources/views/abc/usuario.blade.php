@extends('app')

@section('title')
	ABC Usuario
@stop

@section('header')
	<h2>ABC Usuario</h2>
  <hr>
@stop

@section('info')
	<p></p>
@stop

@section('body')
<h3>Creacion de Usuario</h3>
<hr>
	{!! Form::open(['url' => 'a_usuario']) !!}
		{!! Form::label('Usuario:') !!}
		{!! Form::text('usuario') !!}
		{!! Form::label('Password:') !!}
		{!! Form::text('password') !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Apellido:') !!}
		{!! Form::text('apellido') !!}
		{!! Form::label('Rol:') !!}
		{!! Form::text('rol') !!}
		{!! Form::submit('Crear') !!}
	{!! Form::close() !!}


	<h3>Eliminacion de Usuario</h3>
	<hr>

	{!! Form::open(['url' => 'b_usuario']) !!}
		{!! Form::label('Usuario:') !!}
		{!! Form::select('usuario',$opciones2, array_values($opciones2)[0]) !!}
		{!! Form::submit('Borrar') !!}
	{!! Form::close() !!}

	<h3>Actualizacion de Usuario</h3>
	<hr>
	{!! Form::open(['url' => 'c_usuario']) !!}
		{!! Form::label('Usuario a actualizar:') !!}
		{!! Form::select('usuario',$opciones2, array_values($opciones2)[0]) !!}
		{!! Form::label('Password:') !!}
		{!! Form::text('password') !!}
		{!! Form::label('Nombre:') !!}
		{!! Form::text('nombre') !!}
		{!! Form::label('Apellido:') !!}
		{!! Form::text('apellido') !!}
		{!! Form::label('Rol:') !!}
		{!! Form::text('rol') !!}
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
