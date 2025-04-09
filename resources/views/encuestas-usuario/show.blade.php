@extends('adminlte::page')

@section('template_title')
    {{ $encuestasUsuario->name ?? __('Show') . " " . __('Encuestas Usuario') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Encuestas Usuario</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('encuestas-usuarios.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Encuesta:</strong>
                                    {{ $encuestasUsuario->id_encuesta }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Usuario:</strong>
                                    {{ $encuestasUsuario->id_usuario }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
