@extends('adminlte::page')
@section('template_title')Crear Medio de verificación
@section('content')
    <div class="card">
        <div class="card-header">Nuevo medio de verificación</div>
        <form method="POST" action="{{route('medios-verificacion.store' )}}">
            <div class="card-body">
                @include('medios-verificacion.form')
            </div>
            <div class="card-footer">
                <button class="btn btn-success">Guardar</button>
            </div>
        </form>
    </div>
@endsection