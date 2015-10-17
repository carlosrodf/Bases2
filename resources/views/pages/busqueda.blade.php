@extends('app')

@section('title')
	Busqueda
@stop

@section('header')
	<h2>Resultados de la busqueda</h2>	
@stop

@section('nav_items')
	<li><a href="/">Principal</a></li>
	<li><a href="/registro">Registro</a></li>
@stop

@section('body')
	<table>
	<tr><th>Establecimiento</th><th>Descripcion</th><th>Calificacion</th></tr>
	@foreach ($resultados as $resultado)
		<tr>
		<td>
			<a href="/establecimiento/{{$resultado->establecimiento}}">{{$resultado->nombre}}</a>
		</td>
		<td>
			{{$resultado->descripcion}}
		</td>
		<td>
			<td>
			@if ($resultado->punteo > 6)
				<img src="/images/7balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($resultado->punteo > 5)
				<img src="/images/6balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($resultado->punteo > 4)
				<img src="/images/5balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($resultado->punteo > 3)
				<img src="/images/4balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($resultado->punteo > 2)
				<img src="/images/3balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($resultado->punteo > 1)
				<img src="/images/2balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($resultado->punteo > 0)
				<img src="/images/1balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@else
				<img src="/images/RNA.png" alt="7 ball rating" style="width:100px;height:15px">
			@endif
		</td>
		</td>
		</tr>
	@endforeach
	</table>
@stop
<a href="http://www.w3schools.com">Visit W3Schools.com!</a>
