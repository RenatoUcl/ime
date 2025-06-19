@extends('adminlte::page')

@section('template_title')
    Permisos del Rol
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">Permisos asignados al rol: <strong>{{ $rol->nombre }}</strong></span>
                    </div>
                    <div class="card-body bg-white">
                        @if ($rol->permisos->isEmpty())
                            <p>No hay permisos asignados.</p>
                        @else
                            <ul>
                                @foreach($rol->permisos as $permiso)
                                    <li><strong>{{ $permiso->nombre }}</strong> — {{ $permiso->descripcion }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ route('role.index') }}" class="btn btn-secondary mt-3">Volver</a>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
