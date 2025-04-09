@extends('adminlte::page')

@section('title','Editar de Usuarios')

@section('content')
    <div class="container mt-4">
        <h2>Actualizar usuario</h2>
        <div class="row">
            <div class="col">
                <div class="card mt-3">
                    <div class="card-body">
                        <form action="{{ route('usuarios.update', $user->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <label for="name">Escribe el nombre</label>
                            <input type="text" name="name" id="name" class="form-control" required value="{{ $user->name }}">
                            <button class="btn btn-warning mt-3">Actualizar</button>
                            <a href="{{ route('usuarios.index') }}" class="btn btn-secondary mt-3">Cancelar</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection