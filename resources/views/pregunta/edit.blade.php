@extends('adminlte::page')

@section('template_title')
    Actualizar Pregunta
@endsection

@section('content')
<section class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary mt-3">
                <div class="card-header">
                    <span class="card-title">Actualizar Pregunta</span>
                </div>

                <div class="card-body bg-white">
                    <form method="POST"
                          action="{{ route('pregunta.update', $pregunta->id) }}"
                          role="form"
                          enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        @include('pregunta.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('js')
<script>
    function activa_dependencia() {
        const tipo = document.getElementById('tipo').value;
        document.getElementById('depende').style.display = (tipo == 1) ? 'block' : 'none';
    }

    $(document).ready(function () {
        $('textarea').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview']]
            ]
        });
    });
</script>
@endsection