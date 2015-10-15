@extends('app')

@section('title')
	Busqueda
@stop

@section('header')
	<h2>Resultados de la busqueda</h2>	
@stop

@section('nav_items')
	<li class="current"><a href="#">Principal</a></li>
	<li><a href="/registro">Registro</a></li>
@stop

@section('body')
	<table>
	<tr><th>Establecimiento</th><th>Descripcion</th><th>Calificacion</th></tr>
	@foreach ($resultados as $resultado)
		<tr>
		<td><a href="/establecimiento/{{$resultado->establecimiento}}">{{$resultado->nombre}}</a></td><td>{{$resultado->descripcion}}</td><td>{{$resultado->punteo}}</td>
		</tr>
	@endforeach
	</table>
@stop
<a href="http://www.w3schools.com">Visit W3Schools.com!</a>
