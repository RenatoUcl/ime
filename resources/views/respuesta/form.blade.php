<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_pregunta" class="form-label">{{ __('Id Pregunta') }}</label>
            <input type="text" name="id_pregunta" class="form-control @error('id_pregunta') is-invalid @enderror" value="{{ old('id_pregunta', $respuesta?->id_pregunta) }}" id="id_pregunta" placeholder="Id Pregunta">
            {!! $errors->first('id_pregunta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_alternativa" class="form-label">{{ __('Id Alternativa') }}</label>
            <input type="text" name="id_alternativa" class="form-control @error('id_alternativa') is-invalid @enderror" value="{{ old('id_alternativa', $respuesta?->id_alternativa) }}" id="id_alternativa" placeholder="Id Alternativa">
            {!! $errors->first('id_alternativa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="texto" class="form-label">{{ __('Texto') }}</label>
            <input type="text" name="texto" class="form-control @error('texto') is-invalid @enderror" value="{{ old('texto', $respuesta?->texto) }}" id="texto" placeholder="Texto">
            {!! $errors->first('texto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="valor" class="form-label">{{ __('Valor') }}</label>
            <input type="text" name="valor" class="form-control @error('valor') is-invalid @enderror" value="{{ old('valor', $respuesta?->valor) }}" id="valor" placeholder="Valor">
            {!! $errors->first('valor', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nivel" class="form-label">{{ __('Nivel') }}</label>
            <input type="text" name="nivel" class="form-control @error('nivel') is-invalid @enderror" value="{{ old('nivel', $respuesta?->nivel) }}" id="nivel" placeholder="Nivel">
            {!! $errors->first('nivel', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>