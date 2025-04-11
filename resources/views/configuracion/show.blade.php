@extends('adminlte::page')

@section('template_title')
    {{ $configuracion->name ?? __('Show') . " " . __('Configuracion') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Configuracion</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('configuracions.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Institucion:</strong>
                                    {{ $configuracion->institucion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descripcion:</strong>
                                    {{ $configuracion->descripcion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Objetivos:</strong>
                                    {{ $configuracion->objetivos }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 1:</strong>
                                    {{ $configuracion->color_1 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 2:</strong>
                                    {{ $configuracion->color_2 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 3:</strong>
                                    {{ $configuracion->color_3 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 4:</strong>
                                    {{ $configuracion->color_4 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 5:</strong>
                                    {{ $configuracion->color_5 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 6:</strong>
                                    {{ $configuracion->color_6 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 7:</strong>
                                    {{ $configuracion->color_7 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 8:</strong>
                                    {{ $configuracion->color_8 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 9:</strong>
                                    {{ $configuracion->color_9 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Color 10:</strong>
                                    {{ $configuracion->color_10 }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Icono:</strong>
                                    {{ $configuracion->icono }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Logo Principal:</strong>
                                    {{ $configuracion->logo_principal }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Logo Secundario:</strong>
                                    {{ $configuracion->logo_secundario }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Logo Terciario:</strong>
                                    {{ $configuracion->logo_terciario }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
