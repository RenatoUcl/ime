@extends('adminlte::page')
@section('template_title')Crear Encuesta
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-primary card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">Nueva Encuesta</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('encuesta.store') }}" role="form" enctype="multipart/form-data">
                            @csrf
                            <div class="row padding-1 p-1">
                                <div class="col-md-12">
                                    <div class="form-group mb-2 mb20">
                                        <label for="nombre" class="form-label">Nombre</label>
                                        <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                            value="{{ old('nombre', $encuesta?->nombre) }}" id="nombre" placeholder="Nombre">
                                        {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                    <div class="form-group mb-2 mb20">
                                        <label for="descripcion" class="form-label">Descripcion</label>
                                        <textarea name="descripcion"  class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" placeholder="Descripcion">
                                            {{ old('descripcion', $encuesta?->descripcion) }}
                                        </textarea>
                                        {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
                                    </div>
                                    <div class="form-group mb-2 mb20">
                                        <label for="id_linea" class="form-label">Linea Programatica</label>
                                        <select name="id_linea" id="id_linea" class="form-control">
                                            @if($encuesta->id_linea==0)
                                                <option value="0" selected>Seleccione una Linea programatica</option>
                                            @endif
                                            @foreach ($lineas as $linea)
                                                @if($encuesta->id_linea == $linea->id)
                                                    <option value="{{ $linea->id }}" selected>{{ $linea->nombre }}</option>
                                                @else
                                                    <option value="{{ $linea->id }}">{{ $linea->nombre }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt20 mt-2">
                                    <input type="hidden" name="estado" value="1">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                </div>
                            </div>
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