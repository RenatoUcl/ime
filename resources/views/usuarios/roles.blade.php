@extends('adminlte::page')

@section('title','Roles')

@section('content')
<div class="container">
    <h2>Asignar roles al usuario: {{ $usuario->nombre }} {{ $usuario->ap_paterno }}</h2>

    @if (session('success'))
        <div style="color: green">{{ session('success') }}</div>
    @endif

    <form action="{{ route('usuarios.roles.asignar', $usuario->id) }}" method="POST">
        @csrf

        <div>
            <label>Roles disponibles:</label>
            <div style="margin-left: 15px;">
                @foreach($roles as $rol)
                    <div>
                        <label>
                            <input type="checkbox" name="roles[]" value="{{ $rol->id }}"
                                {{ $usuario->roles->contains($rol->id) ? 'checked' : '' }}>
                            {{ $rol->nombre }}
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <br>
        <button type="submit">Guardar cambios</button>
        <a href="{{ route('usuarios.index') }}">Volver</a>
    </form>
</div>
@endsection

