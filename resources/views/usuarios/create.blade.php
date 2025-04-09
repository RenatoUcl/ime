@extends('adminlte::page')

@section('title','Crear Rol')

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Usuario</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('usuarios.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            @include('usuarios.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
