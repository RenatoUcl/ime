@extends('adminlte::page')

@section('title', 'Nuevo período')

@section('content_header')
    <h1>Nuevo período de aplicación</h1>
@stop

@section('content')

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card">
    <div class="card-header">
        <strong>Encuesta:</strong> {{ $encuesta->nombre }}
    </div>

    <form action="{{ route('encuestas.periodos.store', $encuesta->id) }}" method="POST">
        @csrf

        <div class="card-body">
            <div class="form-group">
                <label>Nombre del período</label>
                <input type="text" name="nombre_periodo" class="form-control"
                       value="{{ old('nombre_periodo') }}" required>
            </div>

            <div class="form-group">
                <label>Fecha desde</label>
                <input type="date" name="fecha_desde" class="form-control"
                       value="{{ old('fecha_desde') }}" required>
            </div>

            <div class="form-group">
                <label>Fecha hasta</label>
                <input type="date" name="fecha_hasta" class="form-control"
                       value="{{ old('fecha_hasta') }}" required>
            </div>

            <div class="form-group">
                <label>Estado</label>
                <select name="estado" class="form-control">
                    <option value="1" selected>Activo</option>
                    <option value="0">Cerrado</option>
                </select>
            </div>
        </div>

        <div class="card-footer">
            <a href="{{ route('encuestas.periodos.index', $encuesta->id) }}" class="btn btn-secondary">
                Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                Guardar
            </button>
        </div>
    </form>
</div>

@stop