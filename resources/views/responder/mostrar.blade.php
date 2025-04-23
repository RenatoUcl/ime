@extends('adminlte::page')
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="card-title">{{ $encuesta->nombre }} | Pregunta: {{ $pregunta->id }}</span>
                            <div class="float-right">
                                <a class="btn btn-info btn-sm" href="{{ route('responder.index') }}"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;&nbsp; Volver</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <h2>{!! $pregunta->texto !!}</h2>
                        <div id="pregunta-container">
                            <form id="form-respuesta">
                                @csrf
                                <input type="hidden" name="id_pregunta" value="{{ $pregunta->id }}" />
                                <input type="hidden" name="id_encuesta" value="{{ $encuesta->id }}" />
                                <input type="hidden" name="index" value="{{ $index }}" />
                            
                                @foreach ($pregunta->alternativas as $alternativa)
                                    <div class="row mb-2 align-middle">
                                            <div class="mr-2">
                                                <input type="radio" name="id_alternativa" value="{{ $alternativa->id }}" data-valor="{{ $alternativa->valor }}" />
                                            </div>
                                            <div>
                                                {!! $alternativa->texto !!}
                                            </div>
                                    </div>
                                @endforeach
                                <input type="hidden" name="valor" id="valor">
                                <button type="submit" class="btn btn-primary mt-3">Siguiente</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
{{-- Incluir jQuery --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Cuando cambia una alternativa seleccionada
            $('input[name="id_alternativa"]').on('change', function() {
                const valor = $(this).data('valor');
                $('#valor').val(valor); // actualiza el campo oculto
            });
            $('#form-respuesta').on('submit', function(e) {
                e.preventDefault();
                console.log('Formulario capturado');
                $.ajax({
                    url: '{{ route("responder.guardar") }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        window.location.href = response.siguiente_url;
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
        </script>

@endsection
