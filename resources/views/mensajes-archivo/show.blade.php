@extends('adminlte::page')

@section('template_title')
    {{ $mensajesArchivo->name ?? __('Show') . " " . __('Mensajes Archivo') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Mensajes Archivo</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('mensajes-archivos.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Mensaje:</strong>
                                    {{ $mensajesArchivo->id_mensaje }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Usuario:</strong>
                                    {{ $mensajesArchivo->id_usuario }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $mensajesArchivo->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Archivo:</strong>
                                    {{ $mensajesArchivo->archivo }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
