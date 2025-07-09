@extends('adminlte::page')

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary card-default mt-3">
                <div class="card-header">
                    <span class="card-title">Asignar rol al usuario: {{ $usuario->nombre }} {{ $usuario->ap_paterno }}</span>
                </div>
                <div class="card-body bg-white">
                    <form action="{{ route('usuarios.roles.asignar', $usuario->id) }}" method="POST">
                        @csrf

                        @foreach($roles as $rol)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="rol" value="{{ $rol->id }}"
                                    id="rol_{{ $rol->id }}"
                                    {{ $usuario->roles->first()?->id === $rol->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="rol_{{ $rol->id }}">
                                    {{ $rol->nombre }}
                                </label>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-primary mt-3">Guardar</button>
                        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-3">Volver</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
