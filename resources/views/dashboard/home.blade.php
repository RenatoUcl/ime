@extends('adminlte::page')

@section('title','IME Dashboard')

@section('content')
    <div class="container">
        <h1>Hola @usuario</h1>
        <a href="{{route('logout')}}" class="btn btn-danger">Salir</a>
    </div>
@endsection