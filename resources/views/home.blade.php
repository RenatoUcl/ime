@extends('adminlte::page')

@section('title','Editar Rol')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="card-header">{{ __('Bienvenido al IME') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <p>Acceso Valido!</p>
                </div>
                <div class="card-footer text-right border-top">
                    <a href="{{route('logout')}}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
