<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_pregunta" class="form-label">{{ __('Id Pregunta') }}</label>
            <input type="text" name="id_pregunta" class="form-control @error('id_pregunta') is-invalid @enderror" value="{{ old('id_pregunta', $cabeceraRespuesta?->id_pregunta) }}" id="id_pregunta" placeholder="Id Pregunta">
            {!! $errors->first('id_pregunta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_alternativa" class="form-label">{{ __('Id Alternativa') }}</label>
            <input type="text" name="id_alternativa" class="form-control @error('id_alternativa') is-invalid @enderror" value="{{ old('id_alternativa', $cabeceraRespuesta?->id_alternativa) }}" id="id_alternativa" placeholder="Id Alternativa">
            {!! $errors->first('id_alternativa', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="respuesta" class="form-label">{{ __('Respuesta') }}</label>
            <input type="text" name="respuesta" class="form-control @error('respuesta') is-invalid @enderror" value="{{ old('respuesta', $cabeceraRespuesta?->respuesta) }}" id="respuesta" placeholder="Respuesta">
            {!! $errors->first('respuesta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>