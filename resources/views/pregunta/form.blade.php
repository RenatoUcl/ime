<div class="row padding-1 p-1">
    <div class="col-md-12">        
        <div class="form-group mb-2 mb20">
            <label for="id_encuesta" class="form-label">Encuesta</label>
            <select class="form-control" name="id_encuesta" id="id_encuesta">
                <option value="{{ old('id_encuesta', $pregunta?->id_encuesta) }}">
                    {{ $encuesta[0]->nombre }}
                </option>
            </select>        
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipo">Tipo Pregunta</label>
            <select name="tipo" id="tipo" class="form-control" onchange="activa_dependencia()" onfocus="activa_dependencia()"> 
                @if (($pregunta->tipo==2) && (isset($preguntas)))
                    <option value="2">Sin Dependencia</option>
                @else 
                    <option value="1">Con Dependencia</option>
                @endif
                <option value="0"> ---------- </option>
                <option value="1">Con dependencia</option>
                <option value="2">Sin dependencia</option>
            </select>
        </div>
        <div class="form-group mb-2 mb20" id="depende" style="display: none;">
            <label for="dependede">Pregunta dependiente</label>
            <select name="dependede" id="dependede" class="form-control">
                @if($pregunta->id_dependencia>0)
                    <option value="{{ $pregunta->id_dependencia }}" selected>{{ $preguntas[($pregunta->id_dependencia)-1]->id}} | {{ $preguntas[($pregunta->id_dependencia)-1]->texto}}</option>
                    <option value="0"> ---------- </option>
                @endif
                @if ($preguntas)
                    @foreach ($preguntas as $dpnd)
                        <option value="{{ $dpnd->id }}">{{ $dpnd->id }} | {{ $dpnd->texto }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_subdimension" class="form-label">Subdimension</label>
            <select class="form-control" name="id_subdimension">
                <option value="{{ old('id_encuesta', $pregunta?->id_subdimension) }}">
                    {{ $subdimensiones[($pregunta->id_subdimension)-1]->id }} | {{ $subdimensiones[($pregunta->id_subdimension)-1]->nombre }}
                </option>
                <option value="0"> ---------- </option>
                @foreach($subdimensiones as $subd)
                    <option value="{{ $subd->id }}">{{ $subd->id }} | {{ $subd->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group mb-2 mb20">
            <label for="texto" class="form-label">{{ __('Texto') }}</label>
            <textarea name="texto" class="form-control @error('texto') is-invalid @enderror"id="texto" placeholder="Texto">
                {{ old('texto', $pregunta?->texto) }}
            </textarea>
            {!! $errors->first('texto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="posicion">Posición</label>
            <select name="posicion" id="posicion" class="form-control">
                @if ($pregunta->posicion == 0)
                    <option value="0" selected> --- Seleccione Posición --- </option>
                @else
                    <option value="{{ $pregunta->posicion }}" selected>{{ $pregunta->posicion }}</option>
                    <option value="0"> --- Seleccione Nueva Posición --- </option>
                @endif
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</div>

<script>
    function activa_dependencia(){
        var tipo = document.getElementById("tipo");
        var y = tipo.value;
        if (y == 1){
            document.getElementById("depende").style.display = 'block';
        } else {
            document.getElementById("depende").style.display = 'none';
        }
    }
</script>