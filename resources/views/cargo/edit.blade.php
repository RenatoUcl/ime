@extends('adminlte::page')

@section('title','Editar Cargo')

@section('content')
    <section class="content container-fluid">
        <div class="">
            <div class="col-md-12">

                <div class="card card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">{{ __('Update') }} Rol</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cargo.update', $role->id) }}"  role="form" enctype="multipart/form-data">
                            {{ method_field('POST') }}
                            @csrf
                            @include('cargo.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection