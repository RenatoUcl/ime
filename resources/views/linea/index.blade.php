@extends('adminlte::page')

@section('title','Lineas Programaticas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Lineas Programaticas</span>
                            <div class="float-right">
                                <a href="{{ route('linea.create') }}" class="btn btn-success btn-sm float-right"  data-placement="left">Agregar Nuevo</a>
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
                                        <th>No</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($lineas as $item)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $item->nombre }}</td>
                                            <td>{!! $item->descripcion !!}</td>
                                            <td>{{ $item->estado }}</td>
                                            <td>
                                                <form action="{{ route('linea.destroy', $item->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('linea.show', $item->id) }}"><i class="fa fa-fw fa-eye"></i> Ver</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('linea.edit', $item->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                                    @csrf
                                                    @method('DELETE')
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
                {!! $lineas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
