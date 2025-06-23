@extends('adminlte::page')

@section('template_title')
    {{ __('Create') }} Permiso
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Permiso</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('permiso.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            @include('permiso.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
