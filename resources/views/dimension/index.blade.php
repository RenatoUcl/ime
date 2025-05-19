@extends('adminlte::page')

@section('title','Dimensiones')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Dimensiones</span>
                             <div class="float-right">
                                <a href="{{ route('dimension.create') }}" class="btn btn-success btn-sm float-right"  data-placement="left">Agregar Nuevo</a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif

                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Linea Programatica</th>
                                        <th>Posición</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($dimensiones as $item)
                                        @php
                                            $idlinea = ($item->id_linea)-1;
                                            $lineaNombre = $lineas[$idlinea]->nombre;
                                        @endphp
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->nombre }}</td>
                                            <td>{!! $item->descripcion !!}</td>
                                            <td>{{ $lineaNombre }}</td>
                                            <td>{{ $item->posicion }}</td>
                                            <td>{{ $item->estado }}</td>
                                            <td>
                                                <form action="{{ route('dimension.index', $item->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('dimension.show', $item->id) }}"><i class="fa fa-fw fa-eye"></i> Ver</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('dimension.edit', $item->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> Eliminar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{ $dimensiones->links() }}
            </div>
        </div>
    </div>
@endsection
