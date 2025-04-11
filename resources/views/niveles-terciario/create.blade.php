@extends('adminlte::page')

@section('template_title')
    {{ __('Create') }} Niveles Terciario
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Niveles Terciario</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('niveles-terciario.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf

                            @include('niveles-terciario.form')

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
