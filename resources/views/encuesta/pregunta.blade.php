<form id="form-respuesta" onsubmit="enviarRespuesta(event)">
    <h4>{{ $pregunta->texto }}</h4>

    @foreach ($pregunta->alternativas as $alternativa)
        <label>
            <input type="radio" name="id_alternativa" value="{{ $alternativa->id }}" required>
            {{ $alternativa->texto }}
        </label><br>
    @endforeach

    <input type="hidden" name="id_pregunta" value="{{ $pregunta->id }}">
    <button type="submit">Siguiente</button>
</form>