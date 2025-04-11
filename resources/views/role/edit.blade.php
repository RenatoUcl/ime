@extends('adminlte::page')
@section('title','Editar Rol')
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">Actualizar Rol</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('role.update', $role->id) }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('role.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
