@extends('adminlte::page')

@section('template_title')
    {{ __('Create') }} Cabecera Pregunta
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-primary card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Cabecera Pregunta</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cabecera-pregunta.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('cabecera-pregunta.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
