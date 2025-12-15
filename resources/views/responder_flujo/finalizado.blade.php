@extends('adminlte::page')

@section('title', 'Encuesta Finalizada')

@section('content_header')
    <h1>Encuesta Finalizada</h1>
@stop

@section('content')
<div class="alert alert-success">
    <h4>¡Gracias por completar la encuesta!</h4>
    <p>Sus respuestas se han guardado correctamente.</p>
</div>

<a href="{{ url('/') }}" class="btn btn-primary">
    Volver al inicio
</a>
@stop