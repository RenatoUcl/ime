@extends('adminlte::page')

@section('template_title')
    {{ __('Update') }} Mensajes Archivo
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Mensajes Archivo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('mensajes-archivos.update', $mensajesArchivo->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('mensajes-archivo.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
