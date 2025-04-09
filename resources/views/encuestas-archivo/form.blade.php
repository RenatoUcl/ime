<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="id_encuesta" class="form-label">{{ __('Id Encuesta') }}</label>
            <input type="text" name="id_encuesta" class="form-control @error('id_encuesta') is-invalid @enderror" value="{{ old('id_encuesta', $encuestasArchivo?->id_encuesta) }}" id="id_encuesta" placeholder="Id Encuesta">
            {!! $errors->first('id_encuesta', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $encuestasArchivo?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="archivo" class="form-label">{{ __('Archivo') }}</label>
            <input type="text" name="archivo" class="form-control @error('archivo') is-invalid @enderror" value="{{ old('archivo', $encuestasArchivo?->archivo) }}" id="archivo" placeholder="Archivo">
            {!! $errors->first('archivo', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>