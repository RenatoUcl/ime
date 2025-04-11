@extends('adminlte::page')

@section('title','Editar Departamento')

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">Actualizar Departamento</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('departamento.update', $departamento->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('POST') }}
                            @csrf
                            @include('departamento.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection