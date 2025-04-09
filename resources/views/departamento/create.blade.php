@extends('adminlte::page')

@section('title','Nuevo Departamento')

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">Nuevo Departamento</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('departamento.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            @include('departamento.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
