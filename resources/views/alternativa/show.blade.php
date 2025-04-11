@extends('adminlte::page')

@section('template_title')
    {{ $alternativa->name ?? __('Show') . " " . __('Alternativa') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Alternativa</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('alternativas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Pregunta:</strong>
                                    {{ $alternativa->id_pregunta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Texto:</strong>
                                    {{ $alternativa->texto }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Valor:</strong>
                                    {{ $alternativa->valor }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
