@extends('layouts/main')

@section('titulo','Ingreso Usuarios')

@section('contenido')
<section class="vh-100">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4 text-black">
                <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 mt-5 pt-5 pt-xl-0 mt-xl-n5">
                    <form action="{{route('validar')}}" method="post" style="width: 23rem;">
                        @csrf
                        <img src="{{ asset('assets/img/logo_ime.png'); }}" height='70' class="mb-5" />
                        <h3 class="fw-normal mb-3 pb-3 mt-2" style="letter-spacing: 1px;">Iniciar Sesión</h3>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label" for="email">Correo</label>
                            <input type="email" id="email" name="email" class="form-control form-control-md" />
                        </div>
                        <div data-mdb-input-init class="form-outline mb-4">
                            <label class="form-label" for="password">Contraseña</label>
                            <input type="password" id="password" name="password" class="form-control form-control-md" />
                        </div>
                        <div class="pt-1 mb-4">
                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-info btn-lg btn-block" type="submit">Entrar</button>
                        </div>
                        <!--<p class="small mb-5 pb-lg-2"><a class="text-muted" href="#!">Recuperar contraseña?</a></p>-->
                    </form>
                </div>
            </div>
            <div class="col-sm-8 px-0 d-none d-sm-block">
                <img src="{{ asset('assets/img/back_02.jpg'); }}" alt="Login image" class="w-100 vh-100" style="object-fit: cover; object-position: left;">
            </div>
        </div>
    </div>
</section>
@endsection