@extends('adminlte::page')

@section('template_title')
    {{ __('Update') }} Mensajes Respuesta
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Mensajes Respuesta</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('mensajes-respuestas.update', $mensajesRespuesta->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('mensajes-respuesta.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
