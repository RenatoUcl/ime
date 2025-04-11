@extends('adminlte::page')

@section('template_title')
    {{ __('Update') }} Cabecera Alternativa
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Cabecera Alternativa</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cabecera-alternativas.update', $cabeceraAlternativa->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('PATCH') }}
                            @csrf

                            @include('cabecera-alternativa.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
