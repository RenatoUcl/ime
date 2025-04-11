@extends('adminlte::page')
@section('template_title')Actualizar Permiso
@section('content')
    <section class="content container-fluid">
        <div class="roe">
            <div class="col-md-12">
                <div class="card card-primary card-default mt-3">
                    <div class="card-header card-success">
                        <span class="card-title">Actualizar Permiso</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('permiso.update', $permiso->id) }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('permiso.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
