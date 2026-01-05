<div class="row padding-1 p-1">
    <div class="col-md-12">

        {{-- ENCUESTA --}}
        <div class="form-group mb-2">
            <label for="id_encuesta" class="form-label">Encuesta</label>
            <select class="form-control" name="id_encuesta" id="id_encuesta">
                <option value="{{ $pregunta->id_encuesta }}">
                    {{ $encuesta->nombre ?? '' }}
                </option>
            </select>
        </div>

        {{-- TIPO --}}
        <div class="form-group mb-2">
            <label for="tipo">Tipo Pregunta</label>
            <select name="tipo"
                    id="tipo"
                    class="form-control"
                    onchange="activa_dependencia()"
                    onfocus="activa_dependencia()">

                <option value="0"> ---------- </option>
                <option value="1" {{ $pregunta->tipo == 1 ? 'selected' : '' }}>
                    Con dependencia
                </option>
                <option value="2" {{ $pregunta->tipo == 2 ? 'selected' : '' }}>
                    Sin dependencia
                </option>
            </select>
        </div>

        {{-- DEPENDENCIA --}}
        <div class="form-group mb-2"
                id="depende"
                style="{{ $pregunta->tipo == 1 ? '' : 'display:none;' }}">
            <label for="dependede">Pregunta dependiente</label>
            <select name="dependede" id="dependede" class="form-control">

                @if($pregunta->id_dependencia > 0)
                    @php
                        $dependiente = $preguntas->firstWhere('id', $pregunta->id_dependencia);
                    @endphp

                    @if($dependiente)
                        <option value="{{ $dependiente->id }}" selected>
                            {{ $dependiente->id }} | {{ $dependiente->texto }}
                        </option>
                    @endif
                @endif

                <option value="0"> ---------- </option>

                @foreach ($preguntas as $dpnd)
                    <option value="{{ $dpnd->id }}">
                        {{ $dpnd->id }} | {{ $dpnd->texto }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- SUBDIMENSION --}}
        <div class="form-group mb-2">
            <label for="id_subdimension" class="form-label">Subdimensión</label>
            <select class="form-control" name="id_subdimension">

                @php
                    $subActual = $subdimensiones->firstWhere('id', $pregunta->id_subdimension);
                @endphp

                @if($subActual)
                    <option value="{{ $subActual->id }}" selected>
                        {{ $subActual->id }} | {{ $subActual->nombre }}
                    </option>
                @endif

                <option value="0"> ---------- </option>

                @foreach($subdimensiones as $subd)
                    <option value="{{ $subd->id }}">
                        {{ $subd->id }} | {{ $subd->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- TEXTO --}}
        <div class="form-group mb-2">
            <label for="texto" class="form-label">Texto</label>
            <textarea name="texto"
                        id="texto"
                        class="form-control @error('texto') is-invalid @enderror"
                        placeholder="Texto">{{ old('texto', $pregunta->texto) }}</textarea>

            {!! $errors->first('texto', '<div class="invalid-feedback"><strong>:message</strong></div>') !!}
        </div>

        {{-- POSICION --}}
        <div class="form-group mb-2">
            <label for="posicion">Posición</label>
            <select name="posicion" id="posicion" class="form-control">
                <option value="0"> --- Seleccione Posición --- </option>
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}" {{ $pregunta->posicion == $i ? 'selected' : '' }}>
                        {{ $i }}
                    </option>
                @endfor
            </select>
        </div>

    </div>

    <div class="col-md-12 mt-2">
        <button type="submit" class="btn btn-primary">
            Guardar
        </button>
    </div>
</div>