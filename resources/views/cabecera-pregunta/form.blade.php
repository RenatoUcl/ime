<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_encuesta" class="form-label">{{ __('Id Encuesta') }}</label>
            <input type="text" class="form-control" name="id_encuesta" id="id_encuesta" disabled value="{{ $id }}" />
            <!--
            <input type="text" name="id_encuesta" class="form-control @error('id_encuesta') is-invalid @enderror" value="{{ old('id_encuesta', $cabeceraPregunta?->id_encuesta) }}" id="id_encuesta" placeholder="Id Encuesta">
            {!! $errors->first('id_encuesta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
            -->
        </div>
        <div class="form-group mb-2 mb20">
            <label for="tipo" class="form-label">{{ __('Tipo') }}</label>
            <input type="text" name="tipo" class="form-control @error('tipo') is-invalid @enderror" value="{{ old('tipo', $cabeceraPregunta?->tipo) }}" id="tipo" placeholder="Tipo">
            {!! $errors->first('tipo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="pregunta" class="form-label">{{ __('Pregunta') }}</label>
            <input type="text" name="pregunta" class="form-control @error('pregunta') is-invalid @enderror" value="{{ old('pregunta', $cabeceraPregunta?->pregunta) }}" id="pregunta" placeholder="Pregunta">
            {!! $errors->first('pregunta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="estado" class="form-label">{{ __('Estado') }}</label>
            <input type="text" name="estado" class="form-control @error('estado') is-invalid @enderror" value="{{ old('estado', $cabeceraPregunta?->estado) }}" id="estado" placeholder="Estado">
            {!! $errors->first('estado', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>