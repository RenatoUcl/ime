@extends('adminlte::page')
@section('template_title') Alternativa
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">Actualizar Alternativa</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('alternativa.update', $alternativa->id) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('alternativa.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
