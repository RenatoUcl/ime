@extends('adminlte::page')
@section('template_title')Detalle Término de verificación
@section('content')
    <div class="card">
        <div class="card-header">Detalle término</div>
        <div class="card-body">
            <h3>{{$termino->termino}}</h3>
            <hr>
            <h5>Subdimensiones asociadas</h5>
            <ul>
                @foreach($termino->subdimensiones as $sub)
                    <li>{{$sub->nombre}}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection