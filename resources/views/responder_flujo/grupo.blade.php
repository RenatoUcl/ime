@extends('adminlte::page')

@section('title', 'Responder Encuesta')

@section('content_header')
    <h1>{{ $encuesta->nombre }}</h1>
@stop

@section('content')

<div class="card">

    <div class="card-header">
        <h4><strong>Dimensión:</strong> {{ $dimension->nombre }}</h4>
        <p>{!! $dimension->descripcion !!}</p>
        <hr>
        <h5><strong>Subdimensión:</strong> {{ $subdimension->nombre }}</h5>
        <p>{{ $subdimension->descripcion }}</p>
    </div>

    <div class="card-body">

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

        @forelse($preguntas as $pregunta)

            @php
                $respuestaGuardada = $respuestasUsuario->get($pregunta->id);
                $esDependiente = !empty($pregunta->id_dependencia) && (int)$pregunta->id_dependencia !== 0;
            @endphp

            <div class="mb-4 p-3 border rounded pregunta"
                 data-pregunta-id="{{ $pregunta->id }}"
                 data-depende-de="{{ $esDependiente ? (int)$pregunta->id_dependencia : 0 }}"
                 data-alt-real=""
                 style="{{ $esDependiente ? 'display:none;' : '' }}"
            >
                <div class="mb-2 pregunta-texto">
                    {!! $pregunta->texto !!}
                </div>

                <div class="pregunta-texto-original d-none">
                    {!! $pregunta->texto !!}
                </div>

                @if($esDependiente)
                    {{-- metas: alternativas que determinan el texto dependiendo de la alternativa elegida en la pregunta padre --}}
                    @foreach($pregunta->alternativas as $alt)
                        <div class="alt-meta d-none"
                             data-parent-alt-id="{{ (int)$alt->id_dependencia }}"
                             data-alt-id="{{ (int)$alt->id }}">
                            {!! $alt->texto !!}
                        </div>
                    @endforeach

                    {{-- SI / NO (valor = 1 / 0), PERO el alternativa_id real viene del meta seleccionado --}}
                    <div class="form-check mb-2">
                        <input type="radio"
                               class="form-check-input respuesta respuesta-dependiente"
                               name="preg_{{ $pregunta->id }}"
                               id="preg_{{ $pregunta->id }}_si"
                               data-pregunta="{{ $pregunta->id }}"
                               data-grupo="{{ $grupo }}"
                               data-valor="1"
                               @if($respuestaGuardada && (int)$respuestaGuardada->valor === 1)
                                   checked
                               @endif
                        >
                        <label class="form-check-label" for="preg_{{ $pregunta->id }}_si">SI</label>
                    </div>

                    <div class="form-check mb-2">
                        <input type="radio"
                               class="form-check-input respuesta respuesta-dependiente"
                               name="preg_{{ $pregunta->id }}"
                               id="preg_{{ $pregunta->id }}_no"
                               data-pregunta="{{ $pregunta->id }}"
                               data-grupo="{{ $grupo }}"
                               data-valor="0"
                               @if($respuestaGuardada && (int)$respuestaGuardada->valor === 0)
                                   checked
                               @endif
                        >
                        <label class="form-check-label" for="preg_{{ $pregunta->id }}_no">NO</label>
                    </div>

                @else
                    {{-- pregunta normal --}}
                    @foreach($pregunta->alternativas as $alt)
                        <div class="form-check mb-2">
                            <input type="radio"
                                   class="form-check-input respuesta respuesta-normal"
                                   name="preg_{{ $pregunta->id }}"
                                   id="alt_{{ $alt->id }}"
                                   data-pregunta="{{ $pregunta->id }}"
                                   data-grupo="{{ $grupo }}"
                                   data-alt-id="{{ (int)$alt->id }}"
                                   value="{{ (int)$alt->id }}"
                                   @if($respuestaGuardada && (int)$respuestaGuardada->id_alternativa === (int)$alt->id)
                                       checked
                                   @endif
                            >
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

    function actualizarBarra(porcentaje) {
        let barra = document.querySelector('.progress-bar');
        if (!barra) return;
        barra.style.width = porcentaje + "%";
        barra.textContent = porcentaje + "%";
        barra.setAttribute('aria-valuenow', porcentaje);
    }

    function ocultarPreguntaDependiente(pregDiv) {
        pregDiv.style.display = 'none';
        pregDiv.dataset.altReal = '';
        pregDiv.querySelectorAll("input[type=radio]").forEach(r => r.checked = false);
    }

    function mostrarPreguntaDependiente(pregDiv, altMetaMatch) {
        let originalEl = pregDiv.querySelector('.pregunta-texto-original');
        let textoEl    = pregDiv.querySelector('.pregunta-texto');

        let textoAlt  = altMetaMatch.innerHTML;
        let textoOrig = originalEl ? originalEl.innerHTML : '';

        // Texto requerido: texto alternativa + salto + texto pregunta
        textoEl.innerHTML = textoAlt + '<br>' + textoOrig;

        // alternativa real que se guarda (id de alternativa meta)
        pregDiv.dataset.altReal = altMetaMatch.dataset.altId;

        pregDiv.style.display = 'block';
    }

    // 1) Procesar dependencias cuando cambia una respuesta NORMAL
    function procesarDependenciasPorPadre(preguntaID, alternativaID) {
        document.querySelectorAll(".pregunta[data-depende-de='" + preguntaID + "']").forEach(function (pregDiv) {

            let altMetas = pregDiv.querySelectorAll('.alt-meta');
            let match = null;

            altMetas.forEach(function(meta) {
                if (meta.dataset.parentAltId == alternativaID) {
                    match = meta;
                }
            });

            if (!match) {
                ocultarPreguntaDependiente(pregDiv);
            } else {
                mostrarPreguntaDependiente(pregDiv, match);
            }
        });
    }

    // 2) Guardar por AJAX
    function guardarAjax(payload) {
        return fetch("{{ route('encuestas.flujo.guardar', $encuesta->id) }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json());
    }

    // Listener respuestas NORMALES
    document.querySelectorAll('.respuesta-normal').forEach(function(input) {
        input.addEventListener('change', function() {
            let preguntaID    = this.dataset.pregunta;
            let alternativaID = this.value;
            let grupo         = this.dataset.grupo;

            // mostrar/ocultar dependientes
            procesarDependenciasPorPadre(preguntaID, alternativaID);

            // guardar normal
            guardarAjax({
                pregunta_id: preguntaID,
                alternativa_id: alternativaID,
                grupo: grupo
            })
            .then(data => {
                if (data.ok && typeof data.porcentaje !== 'undefined') {
                    actualizarBarra(data.porcentaje);
                } else {
                    console.error("Error al guardar:", data);
                }
            })
            .catch(err => console.error("Error AJAX:", err));
        });
    });

    // Listener respuestas DEPENDIENTES (SI/NO)
    document.querySelectorAll('.respuesta-dependiente').forEach(function(input) {
        input.addEventListener('change', function() {
            let preguntaID = this.dataset.pregunta;
            let grupo      = this.dataset.grupo;
            let valor      = this.dataset.valor; // 1 o 0

            let contenedor = this.closest('.pregunta');
            let altReal = contenedor ? contenedor.dataset.altReal : '';

            if (!altReal) {
                // Esto pasa si la dependiente está oculta o aún no se definió por el padre
                console.warn("Pregunta dependiente sin alternativa real definida. No se guarda.");
                return;
            }

            guardarAjax({
                pregunta_id: preguntaID,
                alternativa_id: parseInt(altReal),
                valor: parseInt(valor),
                grupo: grupo
            })
            .then(data => {
                if (data.ok && typeof data.porcentaje !== 'undefined') {
                    actualizarBarra(data.porcentaje);
                } else {
                    console.error("Error al guardar:", data);
                }
            })
            .catch(err => console.error("Error AJAX:", err));
        });
    });

    // Inicializar dependencias al cargar (si hay radios normales ya marcados)
    document.querySelectorAll('.respuesta-normal:checked').forEach(function(input) {
        let preguntaID    = input.dataset.pregunta;
        let alternativaID = input.value;
        procesarDependenciasPorPadre(preguntaID, alternativaID);
    });

    // Si una dependiente ya tenía respuesta guardada, debe quedar visible:
    // (Luego de procesar los padres, ya queda visible; el checked se respeta)
});
</script>
@stop