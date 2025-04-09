@extends('adminlte::page')

@section('template_title')
    {{ $nivelesPrimario->name ?? __('Show') . " " . __('Niveles Primario') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="float-left">
                            <span class="card-title">{{ __('Show') }} Niveles Primario</span>
                        </div>
                        <div class="float-right">
                            <a class="btn btn-primary btn-sm" href="{{ route('niveles-primarios.index') }}"> {{ __('Back') }}</a>
                        </div>
                    </div>

                    <div class="card-body bg-white">
                        
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Linea Programatica:</strong>
                                    {{ $nivelesPrimario->id_linea_programatica }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Id Rol:</strong>
                                    {{ $nivelesPrimario->id_rol }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Nombre:</strong>
                                    {{ $nivelesPrimario->nombre }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Descripcion:</strong>
                                    {{ $nivelesPrimario->descripcion }}
                                </div>
                                <div class="form-group mb-2 mb20">
                                    <strong>Estado:</strong>
                                    {{ $nivelesPrimario->estado }}
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
