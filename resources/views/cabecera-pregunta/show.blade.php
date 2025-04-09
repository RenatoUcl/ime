@extends('adminlte::page')

@section('template_title')
    {{ $cabeceraPregunta->name ?? __('Show') . " " . __('Cabecera Pregunta') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cabecera Pregunta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cabecera-preguntas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Encuesta:</strong>
                                    {{ $cabeceraPregunta->id_encuesta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Tipo:</strong>
                                    {{ $cabeceraPregunta->tipo }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Pregunta:</strong>
                                    {{ $cabeceraPregunta->pregunta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $cabeceraPregunta->estado }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
