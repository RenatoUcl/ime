@extends('adminlte::page')
@section('title','Roles de Usuarios')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Roles de Usuarios</span>
                            <div class="float-right">
                                <a href="{{ route('role.create') }}" class="btn btn-success btn-sm float-right" data-placement="left">Agregar Nuevo</a>
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
                                    @foreach ($roles as $role)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td >{{ $role->nombre }}</td>
                                            <td >{{ $role->descripcion }}</td>
                                            <td >{{ $role->estado }}</td>
                                            <td>
                                                <form action="{{ route('role.disabled', $role->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('role.show', $role->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('role.edit', $role->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                                    <a class="btn btn-sm btn-info" href="{{ route('role.mostrar',$role->id) }}"><i class="fa fa-fw fa-thumbs-up"></i> Permisos</a>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $roles->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
