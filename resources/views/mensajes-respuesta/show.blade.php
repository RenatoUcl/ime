@extends('adminlte::page')

@section('template_title')
    {{ $mensajesRespuesta->name ?? __('Show') . " " . __('Mensajes Respuesta') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Mensajes Respuesta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('mensajes-respuestas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Mensaje:</strong>
                                    {{ $mensajesRespuesta->id_mensaje }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Usuario:</strong>
                                    {{ $mensajesRespuesta->id_usuario }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Respuesta:</strong>
                                    {{ $mensajesRespuesta->respuesta }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
