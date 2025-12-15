@extends('adminlte::page')

@section('title', 'Instancias de Encuesta')

@section('content_header')
    <h1>Instancias de: {{ $encuesta->nombre }}</h1>
@stop

@section('content')

<a href="{{ route('encuestas.instancias.create', $encuesta->id) }}" class="btn btn-primary mb-3">
    Crear nueva instancia
</a>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Fecha Inicio</th>
            <th>Fecha Fin</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($instancias as $inst)
            <tr>
                <td>{{ $inst->id }}</td>
                <td>{{ $inst->fecha_inicio }}</td>
                <td>{{ $inst->fecha_fin }}</td>
                <td>
                    @if ($inst->activa)
                        <span class="badge bg-success">Abierta</span>
                    @else
                        <span class="badge bg-secondary">Cerrada</span>
                    @endif
                </td>
                <td>
                    @if ($inst->activa)
                        <form action="{{ route('encuestas.instancias.close', [$encuesta->id, $inst->id]) }}"
                            method="POST"
                            onsubmit="return confirm('¿Cerrar instancia?');">
                            @csrf
                            <button class="btn btn-danger btn-sm">Cerrar</button>
                        </form>
                    @else
                        <em>—</em>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

@stop
