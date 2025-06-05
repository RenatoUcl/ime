@extends('adminlte::page')

@section('content')
<style>
    /* Estilos Generales */
    body {
        font-family: sans-serif;
        background-color: #f7fafc; /* bg-gray-100 */
        color: #2d3748; /* text-gray-800 */
        line-height: 1.5;
    }

    .container {
        width: 90%;
        max-width: 900px; /* Ajusta según necesidad */
        margin-left: auto;
        margin-right: auto;
        padding: 1rem;
    }

    .survey-main-container {
        background-color: #fff; /* bg-white */
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* shadow-md */
        border-radius: 0.5rem; /* rounded-lg */
        padding: 1.5rem; /* p-6 */
    }

    h1.survey-title {
        font-size: 1.5rem; /* text-2xl */
        font-weight: bold; /* font-bold */
        margin-bottom: 0.5rem; /* mb-2 */
        color: #4a5568; /* text-gray-700 */
    }

    p.survey-description {
        color: #718096; /* text-gray-600 */
        margin-bottom: 1.5rem; /* mb-6 */
    }

    /* Alertas y Mensajes */
    .alert-info {
        background-color: #fefcbf; /* bg-yellow-100 */
        border-left: 4px solid #fbd38d; /* border-yellow-500 */
        color: #b7791f; /* text-yellow-700 */
        padding: 1rem; /* p-4 */
        margin-bottom: 1rem;
    }
    .alert-info .font-bold {
        font-weight: bold;
    }

    /* Formulario y Grupos de Preguntas */
    #form-encuesta {
        /* space-y-8 equivalente aproximado */
    }
    #form-encuesta > .grupo-preguntas + .grupo-preguntas {
         margin-top: 2rem; /* space-y-8 */
    }


    .grupo-preguntas {
        padding: 1.25rem; /* p-5 */
        border: 1px solid #e2e8f0; /* border-gray-200 */
        border-radius: 0.5rem; /* rounded-lg */
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /* shadow-sm */
    }

    .grupo-preguntas.hidden {
        display: none !important;
    }

    .subdimension-header {
        margin-bottom: 1rem; /* mb-4 */
        padding-bottom: 0.5rem; /* pb-2 */
        border-bottom: 1px solid #cbd5e0; /* border-gray-300 */
    }

    .subdimension-title {
        font-size: 1.25rem; /* text-xl */
        font-weight: 600; /* font-semibold */
        color: #4299e1; /* text-blue-600 */
    }

    .subdimension-description {
        font-size: 0.875rem; /* text-sm */
        color: #a0aec0; /* text-gray-500 */
        margin-top: 0.25rem; /* mt-1 */
    }

    /* Preguntas */
    .pregunta {
        margin-bottom: 1.5rem; /* mb-6 */
        padding: 1rem; /* p-4 */
        background-color: #f7fafc; /* bg-gray-50 */
        border-radius: 0.375rem; /* rounded-md */
        border: 1px solid #e2e8f0; /* border-gray-200 */
    }
    .pregunta.border-error { /* Reemplazo de border-red-400 */
        border-color: #f56565; /* text-red-400 */
    }


    .pregunta-texto {
        font-weight: 500; /* font-medium */
        color: #2d3748; /* text-gray-800 */
        margin-bottom: 0.75rem; /* mb-3 */
    }

    .pregunta-numero {
        color: #63b3ed; /* text-blue-500 */
        font-weight: 600; /* font-semibold */
    }

    /* Alternativas y Entradas */
    .alternativas-container { /* Reemplazo de space-y-2 */
    }
    .alternativas-container > label + label {
        margin-top: 0.5rem; /* space-y-2 */
    }


    .alternativa-label {
        display: flex;
        align-items: center;
        padding: 0.5rem; /* p-2 */
        border-radius: 0.25rem; /* rounded */
        cursor: pointer;
    }
    .alternativa-label:hover {
        background-color: #edf2f7; /* hover:bg-gray-100 */
    }

    .alternativa-label input[type="radio"],
    .alternativa-label input[type="checkbox"] {
        margin-right: 0.75rem; /* mr-3 */
        height: 1rem; /* h-4 */
        width: 1rem; /* w-4 */
        /* Estilos para inputs (pueden variar por navegador, esto es básico) */
        border: 1px solid #cbd5e0; /* border-gray-300 */
    }
    /* Para colores específicos de radio/checkbox, se puede requerir más CSS o JS */

    .alternativa-texto {
        color: #4a5568; /* text-gray-700 */
    }

    textarea.form-textarea {
        width: 100%;
        padding: 0.5rem; /* p-2 */
        border: 1px solid #cbd5e0; /* border-gray-300 */
        border-radius: 0.375rem; /* rounded-md */
    }
    textarea.form-textarea:focus {
        outline: none;
        border-color: #4299e1; /* focus:border-blue-500 */
        box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.5); /* focus:ring-blue-500 (aproximado) */
    }

    .pregunta-tipo-no-configurado {
        font-size: 0.875rem; /* text-sm */
        color: #e53e3e; /* text-red-500 */
    }

    .error-message {
        color: #c53030; /* text-red-600 */
        font-size: 0.875rem; /* text-sm */
        margin-top: 0.25rem; /* mt-1 */
    }

    /* Botones de Navegación */
    .navigation-buttons {
        margin-top: 2rem; /* mt-8 */
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn {
        font-weight: bold; /* font-bold */
        padding: 0.5rem 1rem; /* py-2 px-4 */
        border-radius: 0.375rem; /* rounded-md */
        transition: background-color 0.15s ease-in-out;
        border: none;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
    }
    .btn svg {
        height: 1.25rem; /* h-5 */
        width: 1.25rem; /* w-5 */
        fill: currentColor;
    }
    .btn-anterior svg { margin-right: 0.25rem; }
    .btn-siguiente svg, .btn-finalizar svg { margin-left: 0.25rem; }


    .btn-gray {
        background-color: #a0aec0; /* bg-gray-500 */
        color: white;
    }
    .btn-gray:hover {
        background-color: #718096; /* hover:bg-gray-600 */
    }
    .btn-gray:disabled {
        opacity: 0.5; /* disabled:opacity-50 */
        cursor: not-allowed; /* disabled:cursor-not-allowed */
    }

    .btn-blue {
        background-color: #4299e1; /* bg-blue-500 */
        color: white;
    }
    .btn-blue:hover {
        background-color: #3182ce; /* hover:bg-blue-600 */
    }

    .btn-green {
        background-color: #48bb78; /* bg-green-500 */
        color: white;
    }
    .btn-green:hover {
        background-color: #38a169; /* hover:bg-green-600 */
    }
    .btn.hidden {
        display: none !important;
    }


    /* Progreso */
    #progreso-encuesta {
        font-size: 0.875rem; /* text-sm */
        color: #718096; /* text-gray-600 */
    }

    /* Mensaje Final */
    #mensaje-final {
        margin-top: 1.5rem; /* mt-6 */
        padding: 1rem; /* p-4 */
        background-color: #f0fff4; /* bg-green-100 */
        border-left: 4px solid #68d391; /* border-green-500 */
        color: #2f855a; /* text-green-700 */
        border-radius: 0.375rem; /* rounded-md */
    }
    #mensaje-final.hidden {
        display: none !important;
    }
    #mensaje-final .font-bold {
        font-weight: bold;
    }


    /* Indicador de Carga */
    #loading-indicator {
        position: fixed;
        inset: 0;
        background-color: rgba(45, 55, 72, 0.5); /* bg-gray-800 bg-opacity-50 */
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 50;
    }
    #loading-indicator.hidden {
        display: none !important;
    }

    .loading-box {
        background-color: white; /* bg-white */
        padding: 1.25rem; /* p-5 */
        border-radius: 0.5rem; /* rounded-lg */
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); /* shadow-xl */
        display: flex;
        align-items: center;
    }
    .loading-box svg { /* animate-spin h-5 w-5 text-blue-500 */
        animation: spin 1s linear infinite;
        height: 1.25rem;
        width: 1.25rem;
        color: #4299e1;
        margin-right: 0.75rem; /* space-x-3 */
    }
    .loading-box p {
        font-size: 1.125rem; /* text-lg */
        color: #4a5568; /* text-gray-700 */
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    /* Utilidad para ocultar elementos */
    .hidden {
        display: none !important;
    }

</style>
<section class="content container-fluid">
    <div class="container">
        <div class="survey-main-container">
            <h1 class="survey-title">{{ $encuesta->nombre }}</h1>
            <p class="survey-description">{{ $encuesta->descripcion }}</p>

            <div id="contenedor-encuesta">
                @if($gruposDePreguntas->isEmpty())
                    <div class="alert-info" role="alert">
                        <p class="font-bold">Información</p>
                        <p>Esta encuesta no tiene preguntas asignadas o agrupadas por subdimensiones.</p>
                    </div>
                @else
                    <form id="form-encuesta">
                        @csrf
                        <input type="hidden" name="encuesta_id" value="{{ $encuesta->id }}">
                        <input type="hidden" id="current_group_index" value="0">
                        <input type="hidden" id="total_groups" value="{{ $totalGrupos }}">

                        @foreach($gruposDePreguntas as $index => $grupo)
                            <div class="cabecera-dimension {{ $index == 0 ? '' : 'hidden' }}">
                                <h2 class="subdimension-title">Dimensión: {{ $grupo['dimension'] }}</h2>
                                <p class="subdimension-description">{{ $grupo['dimension_descripcion'] }}</p>
                            </div>

                            <div class="grupo-preguntas {{ $index == 0 ? '' : 'hidden' }}" data-group-index="{{ $index }}">
                                <div class="subdimension-header">
                                    
                                    <div>
                                        <h2 class="subdimension-title">
                                            Subdimensión: {{ $grupo['subdimension_nombre'] }}
                                        </h2>
                                    
                                        @if($grupo['subdimension_descripcion'])
                                        <p class="subdimension-description">{{ $grupo['subdimension_descripcion'] }}</p>
                                        @endif
                                    </div>
                                </div>

                                @foreach($grupo['preguntas'] as $pregunta)
                                    <div class="pregunta" data-pregunta-id="{{ $pregunta->id }}">
                                        <p class="pregunta-texto">
                                            <span class="pregunta-numero">{{ $loop->parent->iteration }}.{{ $loop->iteration }}.</span> {!! $pregunta->texto !!}
                                        </p>
                                        <input type="hidden" name="preguntas_mostradas_grupo_{{ $grupo['subdimension_id'] }}[]" value="{{ $pregunta->id }}">

                                        @if($pregunta->tipo == 2) {{-- Ejemplo: Tipo Opción Múltiple (radio buttons) --}}
                                            <div class="alternativas-container">
                                            @foreach($pregunta->alternativas as $alternativa)
                                                <label class="alternativa-label">
                                                    <input type="radio"
                                                        name="respuestas[{{ $pregunta->id }}][alternativa_id]"
                                                        value="{{ $alternativa->id }}">
                                                    <span class="alternativa-texto">{!! $alternativa->texto !!}</span>
                                                </label>
                                            @endforeach
                                            </div>
                                        @elseif($pregunta->tipo == 1) {{-- Ejemplo: Tipo Texto Abierto --}}
                                            <div class="alternativas-container">
                                            @foreach($pregunta->alternativas as $alternativa)
                                                <label class="alternativa-label">
                                                    <input type="radio"
                                                        name="respuestas[{{ $pregunta->id }}][alternativa_id]"
                                                        value="{{ $alternativa->id }}">
                                                    <span class="alternativa-texto">{!! $alternativa->texto !!}</span>
                                                </label>
                                            @endforeach
                                            </div>
                                        @else
                                            <p class="pregunta-tipo-no-configurado">Tipo de pregunta ({{$pregunta->tipo}}) no configurado para visualización.</p>
                                        @endif
                                        <div class="error-message"></div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </form>

                    <div class="navigation-buttons">
                        <button id="btn-anterior" class="btn btn-gray" disabled>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            Anterior
                        </button>
                        <div id="progreso-encuesta">
                            Sección <span id="current-group-display">1</span> de <span id="total-groups-display">{{$totalGrupos}}</span>
                        </div>
                        <button id="btn-siguiente" class="btn btn-blue">
                            Siguiente
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        </button>
                        <button id="btn-finalizar" class="btn btn-green hidden">
                            Finalizar Encuesta
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414L8.414 15l-4.707-4.707a1 1 0 011.414-1.414L8.414 12.172l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                        </button>
                    </div>
                    <div id="mensaje-final" class="hidden">
                        <p class="font-bold">¡Encuesta Completada!</p>
                        <p>Gracias por completar la encuesta. Tus respuestas han sido guardadas.</p>
                    </div>
                @endif
            </div>

            {{-- Indicador de carga --}}
            <div id="loading-indicator" class="hidden">
                <div class="loading-box">
                    <svg fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p>Guardando respuestas...</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    $(document).ready(function() {
        let currentGroupIndex = 0;
        const totalGroups = parseInt($('#total_groups').val(), 10);
        const encuestaId = $('input[name="encuesta_id"]').val();

        function updateProgreso() {
            $('#current-group-display').text(currentGroupIndex + 1);
            $('#total-groups-display').text(totalGroups);
        }

        function showGroup(index) {
            $('.grupo-preguntas').addClass('hidden');
            $(`.grupo-preguntas[data-group-index="${index}"]`).removeClass('hidden');
            $('#current_group_index').val(index);
            updateProgreso();

            $('#btn-anterior').prop('disabled', index === 0);
            if (index === totalGroups - 1) {
                $('#btn-siguiente').addClass('hidden');
                $('#btn-finalizar').removeClass('hidden');
            } else {
                $('#btn-siguiente').removeClass('hidden');
                $('#btn-finalizar').addClass('hidden');
            }
            // Scroll suave al inicio del grupo de preguntas
            $('html, body').animate({
                scrollTop: $(`.grupo-preguntas[data-group-index="${index}"]`).offset().top - 80 // 80px de margen superior
            }, 300);
        }

        function validateCurrentGroup() {
            let isValid = true;
            $(`.grupo-preguntas[data-group-index="${currentGroupIndex}"] .pregunta`).each(function() {
                const preguntaDiv = $(this);
                preguntaDiv.find('.error-message').text(''); // Limpiar errores previos
                preguntaDiv.removeClass('border-red-400').addClass('border-gray-200'); // Resetear borde

                let answered = false;
                const preguntaId = preguntaDiv.data('pregunta-id');

                // Validación para radios
                if (preguntaDiv.find(`input[type="radio"][name="respuestas[${preguntaId}][alternativa_id]"]`).length > 0) {
                    if (preguntaDiv.find(`input[type="radio"][name="respuestas[${preguntaId}][alternativa_id]"]:checked`).length > 0) {
                        answered = true;
                    }
                }
                // Validación para textareas (asumimos que son obligatorias si existen)
                else if (preguntaDiv.find(`textarea[name="respuestas[${preguntaId}][valor_texto]"]`).length > 0) {
                    if (preguntaDiv.find(`textarea[name="respuestas[${preguntaId}][valor_texto]"]`).val().trim() !== '') {
                        answered = true;
                    }
                }
                // Validación para checkboxes (al menos uno debe estar seleccionado)
                else if (preguntaDiv.find(`input[type="checkbox"][name="respuestas[${preguntaId}][alternativas_seleccionadas][]"]`).length > 0) {
                    if (preguntaDiv.find(`input[type="checkbox"][name="respuestas[${preguntaId}][alternativas_seleccionadas][]"]:checked`).length > 0) {
                        answered = true;
                    }
                }
                // Añadir más validaciones para otros tipos de pregunta si es necesario
                else {
                    // Si no es un tipo conocido para validar, asumimos que no requiere respuesta o es un error de configuración
                    // answered = true; // O false si todos los tipos deben ser validados
                }

                // Si la pregunta tiene alternativas o es un textarea, y no fue respondida, marcar como error
                // (Esto asume que todas las preguntas son obligatorias. Ajustar si hay preguntas opcionales)
                const hasInputs = preguntaDiv.find('input, textarea').length > 0;
                if (hasInputs && !answered) {
                    isValid = false;
                    preguntaDiv.find('.error-message').text('Esta pregunta es obligatoria.');
                    preguntaDiv.removeClass('border-gray-200').addClass('border-red-400'); // Resaltar pregunta no respondida
                }
            });
            return isValid;
        }

        function collectAnswersForCurrentGroup() {
            const respuestas = [];
            $(`.grupo-preguntas[data-group-index="${currentGroupIndex}"] .pregunta`).each(function() {
                const preguntaId = $(this).data('pregunta-id');
                const $radioChecked = $(this).find(`input[type="radio"][name="respuestas[${preguntaId}][alternativa_id]"]:checked`);
                const $textarea = $(this).find(`textarea[name="respuestas[${preguntaId}][valor_texto]"]`);
                const $checkboxesChecked = $(this).find(`input[type="checkbox"][name="respuestas[${preguntaId}][alternativas_seleccionadas][]"]:checked`);

                if ($radioChecked.length > 0) {
                    respuestas.push({
                        pregunta_id: preguntaId,
                        alternativa_id: $radioChecked.val(),
                        valor_texto: null
                    });
                } else if ($textarea.length > 0 && $textarea.val().trim() !== '') {
                    respuestas.push({
                        pregunta_id: preguntaId,
                        alternativa_id: null,
                        valor_texto: $textarea.val().trim()
                    });
                } else if ($checkboxesChecked.length > 0) {
                    // Para checkboxes, podrías querer guardar cada selección como una respuesta separada
                    // o enviar un array de IDs de alternativas. La estructura actual del backend
                    // (updateOrCreate por pregunta) es más adecuada para una alternativa por respuesta.
                    // Si una pregunta de checkbox puede tener múltiples respuestas,
                    // necesitarás ajustar el backend y cómo se guardan.
                    // Por ahora, enviaremos la primera seleccionada o podrías concatenar.
                    // Este es un ejemplo simple, ajustar según necesidad:
                    $checkboxesChecked.each(function() {
                        respuestas.push({
                            pregunta_id: preguntaId,
                            alternativa_id: $(this).val(), // Guarda cada checkbox como una entrada de respuesta (requiere ajuste en backend si una pregunta solo tiene una fila en 'respuestas')
                                                        // O enviar un array: 'alternativas_ids': $checkboxesChecked.map((_,el) => el.value).get()
                            valor_texto: null
                        });
                    });
                }
            });
            return respuestas;
        }

        function saveGroupAnswers(callback) {
            const respuestasData = collectAnswersForCurrentGroup();

            // Solo enviar AJAX si hay respuestas para guardar.
            // Si el grupo no tenía preguntas o todas eran opcionales y no se respondieron,
            // `respuestasData` podría estar vacío.
            if (respuestasData.length === 0) {
                // Si no hay datos que enviar (ej. grupo vacío o preguntas opcionales no respondidas),
                // simplemente ejecutar el callback como éxito para pasar al siguiente grupo.
                if (callback) callback(true);
                return;
            }

            $('#loading-indicator').removeClass('hidden');

            $.ajax({
                url: '{{ route("responder.guardarRespuestasGrupo") }}',
                type: 'POST',
                data: {
                    _token: $('input[name="_token"]').val(),
                    encuesta_id: encuestaId,
                    respuestas: respuestasData
                },
                success: function(response) {
                    $('#loading-indicator').addClass('hidden');
                    if (response.success) {
                        if (callback) callback(true);
                    } else {
                        alert('Error al guardar respuestas: ' + (response.message || 'Error desconocido.'));
                        if (callback) callback(false);
                    }
                },
                error: function(xhr) {
                    $('#loading-indicator').addClass('hidden');
                    let errorMsg = 'Error de comunicación al guardar respuestas. Por favor, inténtelo de nuevo.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const parsedError = JSON.parse(xhr.responseText);
                            if (parsedError && parsedError.message) errorMsg = parsedError.message;
                        } catch (e) { /* no hacer nada si no es JSON */ }
                    }
                    alert(errorMsg);
                    console.error("Error guardando respuestas: ", xhr);
                    if (callback) callback(false);
                }
            });
        }

        $('#btn-siguiente').on('click', function() {
            if (!validateCurrentGroup()) {
                // Scroll al primer error
                const firstError = $('.pregunta.border-red-400').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 80
                    }, 300);
                }
                alert('Por favor, responda todas las preguntas obligatorias de esta sección.');
                return;
            }
            saveGroupAnswers(function(success) {
                if (success) {
                    if (currentGroupIndex < totalGroups - 1) {
                        currentGroupIndex++;
                        showGroup(currentGroupIndex);
                    }
                }
            });
        });

        $('#btn-anterior').on('click', function() {
            if (currentGroupIndex > 0) {
                // Nota: Las respuestas del grupo actual NO se guardan al retroceder.
                // Esto es una decisión de diseño. Si deseas guardarlas, llama a saveGroupAnswers.
                currentGroupIndex--;
                showGroup(currentGroupIndex);
            }
        });

        $('#btn-finalizar').on('click', function() {
            if (!validateCurrentGroup()) {
                const firstError = $('.pregunta.border-red-400').first();
                if (firstError.length) {
                    $('html, body').animate({
                        scrollTop: firstError.offset().top - 80
                    }, 300);
                }
                alert('Por favor, responda todas las preguntas obligatorias de esta sección antes de finalizar.');
                return;
            }
            saveGroupAnswers(function(success) {
                if (success) {
                    $('#form-encuesta, #btn-anterior, #btn-siguiente, #btn-finalizar, #progreso-encuesta').addClass('hidden');
                    $('#mensaje-final').removeClass('hidden');
                    $('html, body').animate({ scrollTop: $('#mensaje-final').offset().top - 80 }, 300);
                }
            });
        });

        // Inicializar la visualización del primer grupo y el progreso
        if (totalGroups > 0) {
            showGroup(0);
        } else {
            $('#btn-siguiente, #btn-anterior, #btn-finalizar, #progreso-encuesta').addClass('hidden');
        }
    });
    </script>
</section>
@endsection