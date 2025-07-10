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
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    @if ($message = Session::get('warning'))
                        <div class="alert alert-warning m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>N°</th> 
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Fecha Creación</th>
                                        <th>Estado</th>
                                        <th width="160">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($encuestas as $encuesta)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $encuesta->nombre }}</td>
                                            <td>{!! $encuesta->descripcion !!}</td>
                                            <td>{{ $encuesta->created_at->format('d/m/Y') }}</td>
                                            <td>
                                                @if ($encuesta->estado == true)
                                                    <span class="btn btn-sm btn-success">Activo</span>
                                                @else
                                                    <span class="btn btn-sm btn-warning">Inactivo</span>
                                                @endif
                                            </td>
                                            <td>                                               
                                                <form action="{{ route('encuesta.disabled', $encuesta->id) }}" method="POST">                                                    
                                                    <a class="btn btn-sm btn-warning" href="{{ route('encuesta.edit', $encuesta->id) }}" title="Modificar"><i class="fa fa-fw fa-edit"></i></a>
                                                    <a class="btn btn-sm btn-info" href="{{ route('encuesta.clonar', $encuesta->id) }}" title="Clonar"><i class="fa fa-fw fa-clone"></i></a>
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;" title="Eliminar"><i class="fa fa-fw fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $encuestas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
