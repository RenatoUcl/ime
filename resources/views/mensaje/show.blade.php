@extends('adminlte::page')

@section('template_title')
    {{ $mensaje->name ?? __('Show') . " " . __('Mensaje') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Mensaje</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('mensajes.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Usuario Origen:</strong>
                                    {{ $mensaje->id_usuario_origen }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Usuario Destino:</strong>
                                    {{ $mensaje->id_usuario_destino }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Estado:</strong>
                                    {{ $mensaje->id_estado }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Asunto:</strong>
                                    {{ $mensaje->asunto }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Mensaje:</strong>
                                    {{ $mensaje->mensaje }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Leido:</strong>
                                    {{ $mensaje->leido }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Readed At:</strong>
                                    {{ $mensaje->readed_at }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
