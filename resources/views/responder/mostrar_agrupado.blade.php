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
                                <input class="form-check-input"
                                       type="radio"
                                       name="respuesta[{{ $pregunta->id }}]"
                                       id="alt-{{ $alternativa->id }}"
                                       value="{{ $alternativa->id }}|{{ $alternativa->valor }}">
                                <label class="form-check-label" for="alt-{{ $alternativa->id }}">
                                    {{ $alternativa->texto }}
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
</div>
@endsection
