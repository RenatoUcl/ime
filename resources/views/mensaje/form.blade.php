<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_usuario_origen" class="form-label">{{ __('Id Usuario Origen') }}</label>
            <input type="text" name="id_usuario_origen" class="form-control @error('id_usuario_origen') is-invalid @enderror" value="{{ old('id_usuario_origen', $mensaje?->id_usuario_origen) }}" id="id_usuario_origen" placeholder="Id Usuario Origen">
            {!! $errors->first('id_usuario_origen', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_usuario_destino" class="form-label">{{ __('Id Usuario Destino') }}</label>
            <input type="text" name="id_usuario_destino" class="form-control @error('id_usuario_destino') is-invalid @enderror" value="{{ old('id_usuario_destino', $mensaje?->id_usuario_destino) }}" id="id_usuario_destino" placeholder="Id Usuario Destino">
            {!! $errors->first('id_usuario_destino', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="id_estado" class="form-label">{{ __('Id Estado') }}</label>
            <input type="text" name="id_estado" class="form-control @error('id_estado') is-invalid @enderror" value="{{ old('id_estado', $mensaje?->id_estado) }}" id="id_estado" placeholder="Id Estado">
            {!! $errors->first('id_estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="asunto" class="form-label">{{ __('Asunto') }}</label>
            <input type="text" name="asunto" class="form-control @error('asunto') is-invalid @enderror" value="{{ old('asunto', $mensaje?->asunto) }}" id="asunto" placeholder="Asunto">
            {!! $errors->first('asunto', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="mensaje" class="form-label">{{ __('Mensaje') }}</label>
            <input type="text" name="mensaje" class="form-control @error('mensaje') is-invalid @enderror" value="{{ old('mensaje', $mensaje?->mensaje) }}" id="mensaje" placeholder="Mensaje">
            {!! $errors->first('mensaje', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="leido" class="form-label">{{ __('Leido') }}</label>
            <input type="text" name="leido" class="form-control @error('leido') is-invalid @enderror" value="{{ old('leido', $mensaje?->leido) }}" id="leido" placeholder="Leido">
            {!! $errors->first('leido', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="readed_at" class="form-label">{{ __('Readed At') }}</label>
            <input type="text" name="readed_at" class="form-control @error('readed_at') is-invalid @enderror" value="{{ old('readed_at', $mensaje?->readed_at) }}" id="readed_at" placeholder="Readed At">
            {!! $errors->first('readed_at', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>