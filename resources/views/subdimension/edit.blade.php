@extends('adminlte::page')
@section('template_title')Actualizar Subdimensión
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">Actualizar Subdimensión</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('subdimension.update', $subdimension->id) }}"  role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            @include('subdimension.form')
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
                lang: 'es-ES',
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