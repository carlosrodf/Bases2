@extends('app');

@section('title')
	Reservaciones
@stop

@section('nav_items')
	<li><a href="/user">Perfil</a></li>
	<li class="current"><a href="#">Reservas</a></li>
@stop

@section('header')
	<h2>Reservaciones</h2>
	<hr>
@stop

@section('body')
	<table>
	<tr>
		<th>Establecimiento</th>
		<th>Servicio</th>
		<th>Reservacion</th>
	</tr>
	@foreach ($reservas as $reserva)
		<tr>
			<td>{{$reserva->establecimiento}}</td>
			<td>{{$reserva->nombre}}</td>
			<td>
				{!! Form::open() !!}
					{!! Form::hidden('id_reserva',$reserva->reserva) !!}
					<input type="date" name="fechar" value="{{substr($reserva->fecha,0,-9)}}">
					{!! Form::submit('Actualizar') !!}
				{!! Form::close() !!}
			</td>
		</tr>
	@endforeach
	</table>
	<hr>

	<table>
	<tr>
		<th>Establecimiento</th>
		<th>Servicio</th>
		<th>Fecha</th>
	</tr>
	@foreach ($reservas as $reserva)
		<tr>
			<td>{{$reserva->establecimiento}}</td>
			<td>{{$reserva->nombre}}</td>
			<td>{{substr($reserva->fecha,0,-9)}}</td>
			<td>
				{!! Form::open(['url'=>'/eliminarReserva']) !!}
					{!! Form::hidden('id_reserva',$reserva->reserva) !!}
					{!! Form::submit('Eliminar') !!}
				{!! Form::close() !!}
			</td>
		</tr>
	@endforeach
	</table>

@stop