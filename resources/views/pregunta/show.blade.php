@extends('adminlte::page')

@section('template_title')
    {{ $pregunta->name ?? __('Show') . " " . __('Pregunta') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Pregunta</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('preguntas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Encuesta:</strong>
                                    {{ $pregunta->id_encuesta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Subdimension:</strong>
                                    {{ $pregunta->id_subdimension }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Texto:</strong>
                                    {{ $pregunta->texto }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
