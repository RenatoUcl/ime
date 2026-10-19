@extends('adminlte::page')
@section('template_title')Detalle Medio de verificación
@section('content')
<div class="card">
    <div class="card-header">{{$medio->titulo}}</div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data" action="{{route( 'medios-verificacion.archivo', $medio->id)}}">
            @csrf
            <div class="form-group">
                <label>Agregar documentos</label>
                <input type="file" multiple name="archivo[]" class="form-control">
            </div>
            <button class="btn btn-primary">Subir documentos</button>
        </form>
        <hr>
        <table class="table">
            @foreach($medio->archivos as $archivo)
            <tr>
                <td>{{$archivo->nombre_original}}</td>
                <td>
                    @if($archivo->analisis)
                        <span class="badge badge-success">{{$archivo->analisis->puntaje}}%</span>
                    @else
                        <span class="badge badge-warning">Pendiente</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>

@endsection
