@extends('adminlte::page')

@section('title','Ver Linea Programatica')

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Lineas Programaticas</span>
                            <div class="float-right">
                                <a href="{{ route('linea.index') }}" class="btn btn-success btn-sm float-right"  data-placement="left">Volver</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <div class="form-group mb-2 mb20">
                            <strong>Nombre:</strong>
                            {{ $linea->nombre }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Descripcion:</strong>
                            {!! $linea->descripcion !!}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Estado:</strong>
                            {{ $linea->estado }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
