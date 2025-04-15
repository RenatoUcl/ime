@extends('adminlte::page')
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="card-title">Responder Encuesta</span>
                            <div class="float-right">
                                <a class="btn btn-info btn-sm" href="{{ route('responder.index') }}"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;&nbsp; Volver</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <h2>{{ $encuesta->nombre }}</h2>
                        <div id="pregunta-container">
                            <h4 id="texto-pregunta">{!! $pregunta->texto !!}</h4>
                            <form id="form-respuesta">
                                @csrf
                                <input type="hidden" name="id_pregunta" value="{{ $pregunta->id }}">
                                <input type="hidden" name="id_encuesta" value="{{ $encuesta->id }}">
                                <input type="hidden" name="posicion" value="{{ $pregunta->posicion }}">
                                @foreach ($pregunta->alternativas as $alternativa)
                                    <div>
                                        <label>
                                            <input type="radio" name="id_alternativa" value="{{ $alternativa->id }}">
                                            {!! $alternativa->texto !!}
                                        </label>
                                    </div>
                                @endforeach
                                <button type="submit" class="btn btn-primary mt-3">Siguiente</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Esto va al final del archivo --}}
    <script>
    $(document).ready(function() {
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
                    // Aquí puedes redirigir a la siguiente pregunta
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
