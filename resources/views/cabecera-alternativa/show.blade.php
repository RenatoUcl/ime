@extends('adminlte::page')

@section('template_title')
    {{ $cabeceraAlternativa->name ?? __('Show') . " " . __('Cabecera Alternativa') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Cabecera Alternativa</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('cabecera-alternativas.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Cabecera:</strong>
                                    {{ $cabeceraAlternativa->id_cabecera }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Pregunta:</strong>
                                    {{ $cabeceraAlternativa->pregunta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Orden:</strong>
                                    {{ $cabeceraAlternativa->orden }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
