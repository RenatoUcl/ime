@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $encuesta->titulo }}</h1>

    @foreach($preguntas as $subdimension => $grupo)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <strong>{{ $subdimension }}</strong>
            </div>
            <div class="card-body">
                @foreach($grupo as $pregunta)
                    <div class="mb-3">
                        <p><strong>{{ $pregunta->texto }}</strong></p>
                        @foreach($pregunta->alternativas as $alternativa)
                            <div class="form-check">
                                <input class="form-check-input alternativa"
                                    type="radio"
                                    name="respuesta[{{ $pregunta->id }}]"
                                    id="alt-{{ $alternativa->id }}"
                                    value="{{ $alternativa->id }}|{{ $alternativa->valor }}"
                                    @if($pregunta->respuestaUsuario && $pregunta->respuestaUsuario->id_alternativa == $alternativa->id)
                                            checked
                                    @endif
                                >
                                <!-- 
                                <label class="form-check-label" for="alt-{{ $alternativa->id }}">
                                    {{ $alternativa->texto }}
                                </label>
                                -->
                                <label class="form-check-label alternativa-label
                                    @if($pregunta->respuestaUsuario && $pregunta->respuestaUsuario->id_alternativa == $alternativa->id)
                                        seleccionada
                                    @endif
                                " for="alt-{{ $alternativa->id }}">
                                </label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach

    <form method="POST" action="{{ route('responder.guardarAgrupado') }}">
        @csrf
        <input type="hidden" name="id_encuesta" value="{{ $encuesta->id }}">
        <button type="submit" class="btn btn-primary">Enviar respuestas</button>
    </form>
    <style>
    .alternativa-label {
        padding: 6px 10px;
        border-radius: 6px;
        display: block;
    }

    .alternativa-label.seleccionada {
        background: #d1e7ff;
        border-left: 4px solid #0d6efd;
    }
    </style>
    <script>
    document.querySelectorAll('.alternativa').forEach(radio => {
        radio.addEventListener('change', function() {
            const name = this.name;
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.closest('.form-check').querySelector('.alternativa-label').classList.remove('seleccionada');
            });
            this.closest('.form-check').querySelector('.alternativa-label').classList.add('seleccionada');
        });
    });
    </script>


</div>
@endsection
