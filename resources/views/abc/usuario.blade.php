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
		{!! Form::select('usuario',$opciones, array_values($opciones)[0]) !!}
		{!! Form::submit('Borrar') !!}
	{!! Form::close() !!}

	<h3>Actualizacion de Usuario</h3>
	<hr>
	{!! Form::open(['url' => 'c_usuario']) !!}
		{!! Form::label('Usuario a actualizar:') !!}
		{!! Form::select('usuario',$opciones, array_values($opciones)[0]) !!}
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
	<li><a href="/abc_establecimiento">Establecimiento</a></li>
	<li><a href="/abc_tipo_establecimiento">Tipo Establecimiento</a></li>
	<li><a href="/abc_servicio">Servicio</a></li>
	<li><a href="/abc_tipo_servicio">Tipo Servicio</a></li>
	<li><a href="/abc_dimension">Dimension</a></li>
	<li><a href="/abc_categoria">Categoria</a></li>
	<li><a href="/abc_caracteristica">Caracteristica</a></li>
	<li><a href="/abc_usuario">Usuario</a></li>
@stop
