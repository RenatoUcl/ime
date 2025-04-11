<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_cabecera" class="form-label">{{ __('Id Cabecera') }}</label>
            <input type="text" name="id_cabecera" class="form-control @error('id_cabecera') is-invalid @enderror" value="{{ old('id_cabecera', $cabeceraAlternativa?->id_cabecera) }}" id="id_cabecera" placeholder="Id Cabecera">
            {!! $errors->first('id_cabecera', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="pregunta" class="form-label">{{ __('Pregunta') }}</label>
            <input type="text" name="pregunta" class="form-control @error('pregunta') is-invalid @enderror" value="{{ old('pregunta', $cabeceraAlternativa?->pregunta) }}" id="pregunta" placeholder="Pregunta">
            {!! $errors->first('pregunta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="orden" class="form-label">{{ __('Orden') }}</label>
            <input type="text" name="orden" class="form-control @error('orden') is-invalid @enderror" value="{{ old('orden', $cabeceraAlternativa?->orden) }}" id="orden" placeholder="Orden">
            {!! $errors->first('orden', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>