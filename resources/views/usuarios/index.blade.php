@extends('adminlte::page')

@section('title','Usuarios')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Usuarios</span>
                            <div class="float-right">
                                <a href="{{ route('registro') }}" class="btn btn-success btn-sm float-right"  data-placement="left">Agregar Nuevo</a>
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
<form method="GET" action="{{ route('usuarios.index') }}" class="mb-3">
    <div class="row">
        <div class="col-md-4">
            <input type="text"
                   name="search"
                   class="form-control form-control-sm"
                   placeholder="Buscar por nombre o correo..."
                   value="{{ request('search') }}">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary btn-sm" type="submit">
                Buscar
            </button>
            @if(request('search'))
                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary btn-sm">
                    Limpiar
                </a>
            @endif
        </div>
    </div>
</form>
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>ID</th>                                            
                                        <th>Nombre</th>
                                        <th>Correo</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id; }}</td>    
										<td>{{ $user->nombre }} {{ $user->ap_paterno }} {{ $user->ap_materno }}</td>
										<td>{{ $user->email }}</td>
										<td>{{ $user->estado }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('usuarios.show', $user->id) }}"><i class="fa fa-fw fa-eye"></i> Ver</a>
                                            <a class="btn btn-sm btn-success" href="{{ route('usuarios.edit', $user->id) }}"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                            <a class="btn btn-sm btn-secondary" href="{{ route('usuarios.roles', $user->id) }}"><i class="fas fa-user-lock"></i> Roles</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $users->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
