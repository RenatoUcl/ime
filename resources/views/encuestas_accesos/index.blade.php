@extends('adminlte::page')

@section('title', 'Accesos a Encuestas')

@section('content_header')
    <h1>Accesos a Encuestas</h1>
@stop

@section('content')

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('encuestas.accesos.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Nuevo Acceso
            </a>
        </div>

        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Usuario</th>
                        <th>Encuesta</th>
                        <th>Dimensión</th>
                        <th>Orden</th>
                        <th style="width:120px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accesos as $grupo)
                        <tr>
                            <td>{{ $grupo->first()->usuario->nombre }} {{ $grupo->first()->usuario->ap_paterno }} {{ $grupo->first()->usuario->ap_materno }} || {{ $grupo->first()->usuario->email }}</td>
                            <td>{{ $grupo->first()->encuesta->nombre }}</td>
                            <td>
                                @foreach($grupo as $g)
                                    <span class="badge badge-info">{{ $g->dimension->nombre }}</span>
                                @endforeach
                            </td>
                            <td>{{ $grupo->count() }}</td>

                            <td>
                                <a href="{{ route('encuestas.accesos.edit', $grupo->first()->id) }}"
                                class="btn btn-warning btn-sm"><i class="fa fa-edit"></i></a>

                                <form action="{{ route('encuestas.accesos.destroy', $grupo->first()->id) }}"
                                    method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('¿Eliminar acceso?')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if($accesos->isEmpty())
                <p class="text-center mt-3">No hay accesos configurados.</p>
            @endif
        </div>
    </div>
@stop
