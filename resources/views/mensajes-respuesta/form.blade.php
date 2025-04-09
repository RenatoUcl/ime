<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_mensaje" class="form-label">{{ __('Id Mensaje') }}</label>
            <input type="text" name="id_mensaje" class="form-control @error('id_mensaje') is-invalid @enderror" value="{{ old('id_mensaje', $mensajesRespuesta?->id_mensaje) }}" id="id_mensaje" placeholder="Id Mensaje">
            {!! $errors->first('id_mensaje', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_usuario" class="form-label">{{ __('Id Usuario') }}</label>
            <input type="text" name="id_usuario" class="form-control @error('id_usuario') is-invalid @enderror" value="{{ old('id_usuario', $mensajesRespuesta?->id_usuario) }}" id="id_usuario" placeholder="Id Usuario">
            {!! $errors->first('id_usuario', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="respuesta" class="form-label">{{ __('Respuesta') }}</label>
            <input type="text" name="respuesta" class="form-control @error('respuesta') is-invalid @enderror" value="{{ old('respuesta', $mensajesRespuesta?->respuesta) }}" id="respuesta" placeholder="Respuesta">
            {!! $errors->first('respuesta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>