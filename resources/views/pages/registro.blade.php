@extends('app')

@section('title')
	Registro
@stop

@section('header')
	<h2>Registro de usuarios</h2>	
@stop

@section('info')
	<p>Complete el siguiente formulario para registrarse en OUTGUAT</p>
@stop

@section('body')
	<hr>
	{!! Form::open() !!}
		{!! Form::label('*Nombre de usuario:') !!}
		{!! Form::text('usuario') !!}
		{!! Form::label('*Nombre:') !!}
		{!! Form::text('name') !!}
		{!! Form::label('*Apellido:') !!}
		{!! Form::text('last') !!}
		{!! Form::label('*Contraseña:') !!}
		{!! Form::password('password1') !!}
		{!! Form::label('*Confirme contraseña:') !!}
		{!! Form::password('password2') !!}
		{!! Form::submit('Registrarse') !!}
	{!! Form::close() !!}

	@if (isset($error))
		{{ $error }}
	@endif

@stop

@section('nav_items')
	<li><a href="/">Principal</a></li>
	<li class="current"><a href="#">Registro</a></li>
@stop