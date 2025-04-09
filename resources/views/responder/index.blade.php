@extends('adminlte::page')

@section('template_title')
    Encuestas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Encuestas</span>
                            <div class="float-right">
                                <a href="{{ route('encuesta.create') }}" class="btn btn-success btn-sm float-right" data-placement="left"><i class="fas fa-plus"></i> Crear nueva</a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4"><p>{{ $message }}</p></div>
                    @endif
                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Fecha Creación</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($encuestas as $encuesta)
                                        <tr>
                                            <td>{{ $encuesta->id }}</td>
                                            <td>{{ $encuesta->nombre }}</td>
                                            <td>{{ $encuesta->descripcion }}</td>
                                            <td>{{ $encuesta->created_at->format('d/m/Y') }}</td>
                                            @if ($encuesta->estado == true)
                                                <td>
                                                    <span class="btn btn-sm btn-success">Activo</span>
                                                </td>
                                                <td>
                                                    <a class="btn btn-sm btn-primary" href="{{ route('responder.show', $encuesta->id) }}"><i class="fa fa-fw fa-eye"></i> Responder</a>
                                                </td>
                                            @else
                                                <td><span class="btn btn-sm btn-warning">Inactivo</span></td>
                                                <td></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
@endsection
