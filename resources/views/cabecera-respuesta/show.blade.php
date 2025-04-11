@extends('adminlte::page')

@section('template_title')
    {{ $cabeceraRespuesta->name ?? __('Show') . " " . __('Cabecera Respuesta') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cabecera Respuesta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cabecera-respuestas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Pregunta:</strong>
                                    {{ $cabeceraRespuesta->id_pregunta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Alternativa:</strong>
                                    {{ $cabeceraRespuesta->id_alternativa }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Respuesta:</strong>
                                    {{ $cabeceraRespuesta->respuesta }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
