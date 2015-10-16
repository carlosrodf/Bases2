@extends('app')

@section('title')
	Establecimiento
@stop

@section('header')
	<h2>{{ $datos[0]->nombre}}</h2>	
	<p>{{ $datos[0]->descripcion}}</p>

	@if ($datos[0]->punteo > 6)
		<img src="/images/7balls.png" alt="7 ball rating" style="width:100px;height:15px">
	@elseif ($datos[0]->punteo > 5)
		<img src="/images/6balls.png" alt="7 ball rating" style="width:100px;height:15px">
	@elseif ($datos[0]->punteo > 4)
		<img src="/images/5balls.png" alt="7 ball rating" style="width:100px;height:15px">
	@elseif ($datos[0]->punteo > 3)
		<img src="/images/4balls.png" alt="7 ball rating" style="width:100px;height:15px">
	@elseif ($datos[0]->punteo > 2)
		<img src="/images/3balls.png" alt="7 ball rating" style="width:100px;height:15px">
	@elseif ($datos[0]->punteo > 1)
		<img src="/images/2balls.png" alt="7 ball rating" style="width:100px;height:15px">
	@elseif ($datos[0]->punteo > 0)
		<img src="/images/1balls.png" alt="7 ball rating" style="width:100px;height:15px">
	@else
		<img src="/images/RNA.png" alt="7 ball rating" style="width:100px;height:15px">
	@endif
@stop

@section('nav_items')
@stop

@section('body')
	<table>
	<tr><th>Servicio</th><th>Calificacion</th><th>Calificar</th></tr>
	@foreach ($servicios as $servicio)
		<tr>
		<td>{{$servicio->nombre}}</td>
		<td>
			@if ($servicio->punteo > 6)
				<img src="/images/7balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($servicio->punteo > 5)
				<img src="/images/6balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($servicio->punteo > 4)
				<img src="/images/5balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($servicio->punteo > 3)
				<img src="/images/4balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($servicio->punteo > 2)
				<img src="/images/3balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($servicio->punteo > 1)
				<img src="/images/2balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@elseif ($servicio->punteo > 0)
				<img src="/images/1balls.png" alt="7 ball rating" style="width:100px;height:15px">
			@else
				<img src="/images/RNA.png" alt="7 ball rating" style="width:100px;height:15px">
			@endif
		</td>
		<td>
			{!! Form::open() !!}
				{!! Form::hidden('id_servicio',$servicio->servicio) !!}
				{!! Form::selectRange('esferas',0,7) !!}
				{!! Form::submit('Calificar') !!}
			{!! Form::close() !!}
		</td>
		</tr>
	@endforeach
	</table>
@stop
