@extends('adminlte::page')
@section('template_title')Editar Término de verificación
@section('content')
    <div class="card">
    <div class="card-header">Editar término</div>
        <form method="POST" action="{{route('terminos-verificacion.update',$termino->id)}}">
            @method('PUT')
            <div class="card-body">
                @include('terminos-verificacion.form')
            </div>
            <div class="card-footer">
                <button class="btn btn-primary">Actualizar</button>
            </div>
        </form>
    </div>
@endsection
