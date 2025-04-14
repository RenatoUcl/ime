@extends('adminlte::page')
@section('template_title')Responder Encuesta
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Responder Encuestas</span>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <div id="pregunta-container">
                            <form method="POST" action="{{ route('responder.responder', [$encuesta->id, $pregunta->id]) }}">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
