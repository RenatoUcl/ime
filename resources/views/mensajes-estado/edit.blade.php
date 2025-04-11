@extends('adminlte::page')

@section('template_title')
    {{ __('Update') }} Mensajes Estado
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Mensajes Estado</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('mensajes-estados.update', $mensajesEstado->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('mensajes-estado.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
