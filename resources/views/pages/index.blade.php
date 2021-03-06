@extends('app')

@section('title')
	Login
@stop

@section('header')
	<h2>Login</h2>	
@stop

@section('nav_items')
	<li class="current"><a href="#">Principal</a></li>
	<li><a href="/registro">Registro</a></li>
@stop

@section('sections')
	<section class="box">
		<h2>Login</h2>
		{!! Form::open() !!}
			{!! Form::text('user') !!}
			{!! Form::password('password') !!}
			{!! Form::submit('Ingresar') !!}
		{!! Form::close() !!}

		@if (isset($error))
			{{ $error }}
		@endif
		
	</section>
@stop