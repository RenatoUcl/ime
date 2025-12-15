@extends('adminlte::page')

@section('title', 'Crear Instancia de Encuesta')

@section('content_header')
    <h1>Nueva instancia para: {{ $encuesta->nombre }}</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body">

        <form action="{{ route('encuestas.instancias.store', $encuesta->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label>Fecha de inicio</label>
                <input type="datetime-local" name="fecha_inicio" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Fecha de cierre</label>
                <input type="datetime-local" name="fecha_fin" class="form-control" required>
            </div>

            <button class="btn btn-primary">
                Crear instancia
            </button>

            <a href="{{ route('encuestas.instancias.index', $encuesta->id) }}"
               class="btn btn-secondary">
               Volver
            </a>

        </form>

    </div>
</div>

@stop
