@extends('adminlte::page')
@section('template_title')Crear Termino de verificación
@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Nuevo término</h3>
        </div>
        <form method="POST" action="{{route('terminos-verificacion.store')}}">
            <div class="card-body">
                @include('terminos-verificacion.form')
            </div>
            <div class="card-footer">
                <button class="btn btn-success">Guardar</button>
                <a href="{{route('terminos-verificacion.index')}}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
