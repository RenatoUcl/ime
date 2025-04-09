@extends('adminlte::page')
@section('template_title') Encuesta
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">Actualizar Encuesta</span>
                    </div>
                    <div class="card-body bg-white">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success m-4">
                                <p>{{ $message }}</p>
                            </div>  
                        @endif
                        @if ($message = Session::get('warning'))
                            <div class="alert alert-warning m-4">
                                <p>{{ $message }}</p>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('encuesta.update', $encuesta->id) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('encuesta.form')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            $('textarea').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ]
            });
        });
    </script>
@endsection