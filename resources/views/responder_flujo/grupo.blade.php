@extends('adminlte::page')

@section('title', 'Responder Encuesta')

@section('content_header')
    <h1>{{ $encuesta->nombre }}</h1>
@stop

@section('content')

<div class="card">

    {{-- ENCABEZADO --}}
    <div class="card-header">
        <h4><strong>Dimensión:</strong> {{ $dimension->nombre }}</h4>
        <p>{{ $dimension->descripcion }}</p>
        <hr>
        <h5><strong>Subdimensión:</strong> {{ $subdimension->nombre }}</h5>
        <p>{{ $subdimension->descripcion }}</p>
    </div>

    <div class="card-body">

        {{-- BARRA DE PROGRESO --}}
        <div class="progress mb-4">
            <div class="progress-bar"
                role="progressbar"
                style="width: {{ $porcentaje }}%;"
                aria-valuenow="{{ $porcentaje }}"
                aria-valuemin="0"
                aria-valuemax="100">
                {{ $porcentaje }}%
            </div>
        </div>

        {{-- LISTADO DE PREGUNTAS --}}
        @forelse($preguntas as $pregunta)

            @php
                $respuestaGuardada = $respuestasUsuario->get($pregunta->id);
                $esDependiente = !empty($pregunta->id_dependencia) && $pregunta->id_dependencia != 0;
            @endphp

            <div class="mb-4 p-3 border rounded pregunta"
                data-pregunta-id="{{ $pregunta->id }}"
                data-depende-de="{{ $esDependiente ? $pregunta->id_dependencia : 0 }}"
                style="{{ $esDependiente ? 'display:none;' : '' }}">

                {{-- TEXTO DE LA PREGUNTA --}}
                <p class="mb-2 pregunta-texto">
                    {!! $pregunta->texto !!}
                </p>

                {{-- TEXTO ORIGINAL (solo dependientes) --}}
                <div class="pregunta-texto-original d-none">
                    {!! $pregunta->texto !!}
                </div>

                {{-- META PARA DEPENDENCIAS --}}
                @if($esDependiente)
                    @foreach($pregunta->alternativas as $alt)
                        <div class="alt-meta d-none" data-parent-alt-id="{{ $alt->id_dependencia }}">
                            {!! $alt->texto !!}
                        </div>
                    @endforeach

                    {{-- OPCIONES FIJAS SI/NO --}}
                    <div class="form-check mb-2">
                        <input type="radio"
                            class="form-check-input respuesta"
                            name="preg_{{ $pregunta->id }}"
                            id="preg_{{ $pregunta->id }}_si"
                            data-pregunta="{{ $pregunta->id }}"
                            data-grupo="{{ $grupo }}"
                            value="{{ $pregunta->id }}_1"
                            @if($respuestaGuardada && $respuestaGuardada->valor == 1) checked @endif>
                        <label class="form-check-label" for="preg_{{ $pregunta->id }}_si">SI</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="radio"
                            class="form-check-input respuesta"
                            name="preg_{{ $pregunta->id }}"
                            id="preg_{{ $pregunta->id }}_no"
                            data-pregunta="{{ $pregunta->id }}"
                            data-grupo="{{ $grupo }}"
                            value="{{ $pregunta->id }}_0"
                            @if($respuestaGuardada && $respuestaGuardada->valor == 0) checked @endif>
                        <label class="form-check-label" for="preg_{{ $pregunta->id }}_no">NO</label>
                    </div>

                @else                
                    {{-- ALTERNATIVAS NORMALES --}}
                    @foreach($pregunta->alternativas as $alt)
                        <div class="form-check mb-2">
                            <input type="radio"
                                class="form-check-input respuesta padre"
                                name="preg_{{ $pregunta->id }}"
                                id="alt_{{ $alt->id }}"
                                data-pregunta="{{ $pregunta->id }}"
                                data-grupo="{{ $grupo }}"
                                value="{{ $alt->id }}"
                                @if($respuestaGuardada && $respuestaGuardada->id_alternativa == $alt->id) checked @endif>
                            <label class="form-check-label" for="alt_{{ $alt->id }}">
                                {!! $alt->texto !!}
                            </label>
                        </div>
                    @endforeach
                @endif

            </div>

        @empty
            <div class="alert alert-warning">
                No hay preguntas configuradas para esta subdimensión.
            </div>
        @endforelse

    </div>

    {{-- BOTONES NAVEGACIÓN --}}
    <div class="card-footer d-flex justify-content-between">

        @if($grupo > 1)
            <a href="{{ route('encuestas.flujo.grupo', [$encuesta->id, $grupo - 1]) }}"
            class="btn btn-secondary">← Anterior</a>
        @else
            <span></span>
        @endif

        @if($grupo < $totalPantallas)
            <a href="{{ route('encuestas.flujo.grupo', [$encuesta->id, $grupo + 1]) }}"
            class="btn btn-primary">Siguiente →</a>
        @else
            <a href="{{ route('encuestas.flujo.finalizar', $encuesta->id) }}"
            class="btn btn-success">Finalizar</a>
        @endif
    </div>

</div>

@stop


@section('js')
<script>
document.addEventListener('DOMContentLoaded', function () {

    function procesarDependencias(input) {

        let preguntaID = input.dataset.pregunta;
        let alternativaID = input.value;
        let grupo = input.dataset.grupo;

        // Mostrar / ocultar preguntas dependientes
        document.querySelectorAll(".pregunta[data-depende-de='" + preguntaID + "']")
            .forEach(function (pregDiv) {

                let altMetas = pregDiv.querySelectorAll('.alt-meta');
                let original = pregDiv.querySelector('.pregunta-texto-original');
                let textoEl = pregDiv.querySelector('.pregunta-texto');

                let match = null;

                altMetas.forEach(function (meta) {
                    if (meta.dataset.parentAltId == alternativaID) {
                        match = meta;
                    }
                });

                if (match && original) {
                    textoEl.innerHTML = match.innerHTML + "<br>" + original.innerHTML;
                    pregDiv.style.display = 'block';
                } else {
                    pregDiv.style.display = 'none';
                    pregDiv.querySelectorAll("input[type=radio]").forEach(r => r.checked = false);
                }
            });

        // Guardar por AJAX
        fetch("{{ route('encuestas.flujo.guardar', $encuesta->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                pregunta_id: preguntaID,
                alternativa_id: alternativaID,
                grupo: grupo
            })
        });
    }

    // Listeners
    document.querySelectorAll('.respuesta').forEach(function (input) {
        input.addEventListener('change', function () {
            procesarDependencias(this);
        });
    });

    // Inicialización al cargar
    document.querySelectorAll('.respuesta:checked').forEach(function (input) {
        procesarDependencias(input);
    });

});
</script>
@stop
