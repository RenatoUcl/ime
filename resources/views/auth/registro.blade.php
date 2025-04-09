@extends('adminlte::page')

@section('title','IME :: Registrar usuario')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col mt-5">
                <h2>Formulario de Registro</h2>
                <form action="{{route('registrar')}}" method="post" style="width: 25rem;">
                    @csrf
                    @method('post')
                    <div data-mdb-input-init class="form-outline mb-1">
                        <label class="form-label" for="nombre">Nombre (*)</label>
                        <input type="text" id="nombre" name="nombre" class="form-control form-control-sm" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-1">
                        <label class="form-label" for="ap_paterno">Apellido Paterno  (*)</label>
                        <input type="text" id="ap_paterno" name="ap_paterno" class="form-control form-control-sm" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-1">
                        <label class="form-label" for="ap_materno">Apellido Materno</label>
                        <input type="text" id="ap_materno" name="ap_materno" class="form-control form-control-sm" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-1">
                        <label class="form-label" for="email">Correo  (*)</label>
                        <input type="email" id="email" name="email" class="form-control form-control-sm" placeholder="Ingresa tu correo" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-1">
                        <label class="form-label" for="password">Contraseña  (*)</label>
                        <input type="password" id="password" name="password" class="form-control form-control-sm" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-1">
                        <label class="form-label" for="password_confirmation">Confirmar Contraseña  (*)</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control form-control-sm" />
                    </div>
                    <div data-mdb-input-init class="form-outline mb-1">
                        <label class="form-label" for="telefono">Telefono  (*)</label>
                        <input type="text" id="telefono" name="telefono" class="form-control form-control-sm" />
                    </div>
                    <div class="pt-1 mb-4">
                        <button data-mdb-button-init data-mdb-ripple-init class="btn btn-info btn-sm btn-block" type="submit">Registrarse</button>
                    </div>
                    <p><a href="{{route('login')}}" class="link-info">Volver al login</a></p>
                </form>
            </div>
        </div>
    </div>
@endsection