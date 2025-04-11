@extends('adminlte::page')
@section('title','Ver Dimensión')
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div class="float-left">
                            <span class="card-title">Ver Dimensión</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-success btn-sm" href="{{ route('dimension.index') }}"> Volver</a>
                        </div>
                    </div>
                    <div class="card-body bg-white">            
                        <div class="form-group mb-2 mb20">
                            <strong>Nombre:</strong>
                            {{ $dimension->nombre }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Descripcion:</strong>
                            {!! $dimension->descripcion !!}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Estado:</strong>
                            {{ $dimension->estado }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
