@extends('adminlte::page')

@section('title', 'Períodos de aplicación')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Períodos de aplicación</h1>

        <a href="{{ route('encuestas.periodos.create', $encuesta->id) }}" class="btn btn-primary">
            <i class="fa fa-fw fa-plus"></i> Nuevo período
        </a>
    </div>
@stop

@section('content')

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header">
        <strong>Encuesta:</strong> {{ $encuesta->nombre }}
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nombre del período</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Estado</th>
                    <th style="width: 160px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($periodos as $p)
                    <tr>
                        <td>{{ $p->nombre_periodo }}</td>
                        <td>{{ $p->fecha_desde->format('Y-m-d') }}</td>
                        <td>{{ $p->fecha_hasta->format('Y-m-d') }}</td>
                        <td>
                            @if($p->estado)
                                <span class="badge badge-success">Activo</span>
                            @else
                                <span class="badge badge-secondary">Cerrado</span>
                            @endif
                        </td>
                        <td>
                            <a class="btn btn-sm btn-warning"
                               href="{{ route('encuestas.periodos.edit', $p->id) }}"
                               title="Editar">
                                <i class="fa fa-fw fa-edit"></i>
                            </a>

                            <form action="{{ route('encuestas.periodos.destroy', $p->id) }}"
                                  method="POST"
                                  style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('¿Eliminar este período?');"
                                        title="Eliminar">
                                    <i class="fa fa-fw fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <div class="alert alert-warning mb-0">
                                No hay períodos creados para esta encuesta.
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">
        <a href="{{ route('encuesta.index') }}" class="btn btn-secondary">
            <i class="fa fa-fw fa-arrow-left"></i> Volver a encuestas
        </a>
    </div>
</div>

@stop