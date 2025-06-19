@extends('adminlte::page')

@section('template_title')
    Permisos del Rol
@endsection

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">

            <div class="card card-default">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span class="card-title">Permisos asignados al rol: <strong>{{ $rol->nombre }}</strong></span>

                    <!-- Botón para ir a Asignar Permisos -->
                    <a href="{{ route('role.mostrar', $rol->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-edit"></i> Asignar o Editar Permisos
                    </a>
                </div>

                <div class="card-body bg-white">
                    @if ($rol->permisos->isEmpty())
                        <p>No hay permisos asignados a este rol.</p>
                    @else
                        <ul class="list-group">
                            @foreach($rol->permisos as $permiso)
                                <li class="list-group-item">
                                    <strong>{{ $permiso->nombre }}</strong> — {{ $permiso->descripcion }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <a href="{{ route('role.index') }}" class="btn btn-secondary mt-3">
                        <i class="fas fa-arrow-left"></i> Volver a listado de roles
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
