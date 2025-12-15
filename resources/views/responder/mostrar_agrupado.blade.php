@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $encuesta->titulo }}</h1>

    {{-- FORMULARIO GENERAL --}}
    <form method="POST" action="{{ route('responder.guardarAgrupado') }}">
        @csrf
        <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">

        @php $grupoIndex = 1; @endphp

        @foreach($preguntas as $subdimension => $grupo)
            {{-- INDICAR GRUPO ACTUAL --}}
            <input type="hidden" name="grupo_actual" value="{{ $grupoIndex }}">

            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <strong>{{ $subdimension }}</strong>
                </div>

                <div class="card-body">

                    @foreach($grupo as $pregunta)

                        {{-- ID DE PREGUNTA PARA EL CONTROLADOR --}}
                        <input type="hidden" 
                               name="respuestas[{{ $pregunta->id }}][pregunta_id]" 
                               value="{{ $pregunta->id }}">

                        <div class="mb-3">
                            <p><strong>{{ $pregunta->texto }}</strong></p>

                            @foreach($pregunta->alternativas as $alternativa)
                                <div class="form-check">

                                    {{-- RADIO: alternativa seleccionada --}}
                                    <input  class="form-check-input alternativa"
                                            type="radio"
                                            name="respuestas[{{ $pregunta->id }}][alternativa_id]"
                                            id="alt-{{ $alternativa->id }}"
                                            value="{{ $alternativa->id }}"
                                            @if($pregunta->respuestaUsuario && 
                                                $pregunta->respuestaUsuario->id_alternativa == $alternativa->id)
                                                checked
                                            @endif
                                    >

                                    {{-- VALOR NUMÉRICO DE LA ALTERNATIVA --}}
                                    <input type="hidden"
                                           name="respuestas[{{ $pregunta->id }}][valor_texto]"
                                           value="{{ $alternativa->valor }}">

                                    <label class="form-check-label alternativa-label
                                        @if($pregunta->respuestaUsuario && 
                                            $pregunta->respuestaUsuario->id_alternativa == $alternativa->id)
                                            seleccionada
                                        @endif
                                    " for="alt-{{ $alternativa->id }}">
                                        {{ $alternativa->texto }}
                                    </label>

                                </div>
                            @endforeach

                        </div>
                    @endforeach

                </div>
            </div>

            @php $grupoIndex++; @endphp
        @endforeach

        <button type="submit" class="btn btn-primary">
            Guardar respuestas
        </button>

    </form>

{{-- ESTILOS --}}
<style>
    .alternativa-label {
        padding: 6px 10px;
        border-radius: 6px;
        display: block;
        cursor: pointer;
    }
    .alternativa-label.seleccionada {
        background: #d1e7ff;
        border-left: 4px solid #0d6efd;
    }
</style>

{{-- JAVASCRIPT PARA COLOREAR ALTERNATIVA --}}
<script>
    document.querySelectorAll('.alternativa').forEach(radio => {
        radio.addEventListener('change', function() {
            const name = this.name;
            document.querySelectorAll(`input[name="${name}"]`).forEach(r => {
                r.closest('.form-check')
                    .querySelector('.alternativa-label')
                    .classList.remove('seleccionada');
            });
            this.closest('.form-check')
                .querySelector('.alternativa-label')
                .classList.add('seleccionada');
        });
    });
</script>

</div>
@endsection
