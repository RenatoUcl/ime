@extends('adminlte::page')

@section('template_title')
    {{ $respuesta->name ?? __('Show') . " " . __('Respuesta') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Respuesta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('respuestas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Pregunta:</strong>
                                    {{ $respuesta->id_pregunta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Alternativa:</strong>
                                    {{ $respuesta->id_alternativa }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Texto:</strong>
                                    {{ $respuesta->texto }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Valor:</strong>
                                    {{ $respuesta->valor }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nivel:</strong>
                                    {{ $respuesta->nivel }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
