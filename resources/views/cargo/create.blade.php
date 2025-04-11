@extends('adminlte::page')

@section('title','Nuevo Cargo')

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">

                <div class="card card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">{{ __('Create') }} Cargo</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('cargo.store') }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            @include('cargo.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
