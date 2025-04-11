<div class="row padding-1 p-1">
    <div class="col-md-12">
        
        <div class="form-group mb-2 mb20">
            <label for="institucion" class="form-label">{{ __('Institucion') }}</label>
            <input type="text" name="institucion" class="form-control @error('institucion') is-invalid @enderror" value="{{ old('institucion', $configuracion?->institucion) }}" id="institucion" placeholder="Institucion">
            {!! $errors->first('institucion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">{{ __('Descripcion') }}</label>
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror" value="{{ old('descripcion', $configuracion?->descripcion) }}" id="descripcion" placeholder="Descripcion">
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="objetivos" class="form-label">{{ __('Objetivos') }}</label>
            <input type="text" name="objetivos" class="form-control @error('objetivos') is-invalid @enderror" value="{{ old('objetivos', $configuracion?->objetivos) }}" id="objetivos" placeholder="Objetivos">
            {!! $errors->first('objetivos', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_1" class="form-label">{{ __('Color 1') }}</label>
            <input type="text" name="color_1" class="form-control @error('color_1') is-invalid @enderror" value="{{ old('color_1', $configuracion?->color_1) }}" id="color_1" placeholder="Color 1">
            {!! $errors->first('color_1', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_2" class="form-label">{{ __('Color 2') }}</label>
            <input type="text" name="color_2" class="form-control @error('color_2') is-invalid @enderror" value="{{ old('color_2', $configuracion?->color_2) }}" id="color_2" placeholder="Color 2">
            {!! $errors->first('color_2', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_3" class="form-label">{{ __('Color 3') }}</label>
            <input type="text" name="color_3" class="form-control @error('color_3') is-invalid @enderror" value="{{ old('color_3', $configuracion?->color_3) }}" id="color_3" placeholder="Color 3">
            {!! $errors->first('color_3', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_4" class="form-label">{{ __('Color 4') }}</label>
            <input type="text" name="color_4" class="form-control @error('color_4') is-invalid @enderror" value="{{ old('color_4', $configuracion?->color_4) }}" id="color_4" placeholder="Color 4">
            {!! $errors->first('color_4', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_5" class="form-label">{{ __('Color 5') }}</label>
            <input type="text" name="color_5" class="form-control @error('color_5') is-invalid @enderror" value="{{ old('color_5', $configuracion?->color_5) }}" id="color_5" placeholder="Color 5">
            {!! $errors->first('color_5', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_6" class="form-label">{{ __('Color 6') }}</label>
            <input type="text" name="color_6" class="form-control @error('color_6') is-invalid @enderror" value="{{ old('color_6', $configuracion?->color_6) }}" id="color_6" placeholder="Color 6">
            {!! $errors->first('color_6', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_7" class="form-label">{{ __('Color 7') }}</label>
            <input type="text" name="color_7" class="form-control @error('color_7') is-invalid @enderror" value="{{ old('color_7', $configuracion?->color_7) }}" id="color_7" placeholder="Color 7">
            {!! $errors->first('color_7', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_8" class="form-label">{{ __('Color 8') }}</label>
            <input type="text" name="color_8" class="form-control @error('color_8') is-invalid @enderror" value="{{ old('color_8', $configuracion?->color_8) }}" id="color_8" placeholder="Color 8">
            {!! $errors->first('color_8', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_9" class="form-label">{{ __('Color 9') }}</label>
            <input type="text" name="color_9" class="form-control @error('color_9') is-invalid @enderror" value="{{ old('color_9', $configuracion?->color_9) }}" id="color_9" placeholder="Color 9">
            {!! $errors->first('color_9', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="color_10" class="form-label">{{ __('Color 10') }}</label>
            <input type="text" name="color_10" class="form-control @error('color_10') is-invalid @enderror" value="{{ old('color_10', $configuracion?->color_10) }}" id="color_10" placeholder="Color 10">
            {!! $errors->first('color_10', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="icono" class="form-label">{{ __('Icono') }}</label>
            <input type="text" name="icono" class="form-control @error('icono') is-invalid @enderror" value="{{ old('icono', $configuracion?->icono) }}" id="icono" placeholder="Icono">
            {!! $errors->first('icono', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="logo_principal" class="form-label">{{ __('Logo Principal') }}</label>
            <input type="text" name="logo_principal" class="form-control @error('logo_principal') is-invalid @enderror" value="{{ old('logo_principal', $configuracion?->logo_principal) }}" id="logo_principal" placeholder="Logo Principal">
            {!! $errors->first('logo_principal', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="logo_secundario" class="form-label">{{ __('Logo Secundario') }}</label>
            <input type="text" name="logo_secundario" class="form-control @error('logo_secundario') is-invalid @enderror" value="{{ old('logo_secundario', $configuracion?->logo_secundario) }}" id="logo_secundario" placeholder="Logo Secundario">
            {!! $errors->first('logo_secundario', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="logo_terciario" class="form-label">{{ __('Logo Terciario') }}</label>
            <input type="text" name="logo_terciario" class="form-control @error('logo_terciario') is-invalid @enderror" value="{{ old('logo_terciario', $configuracion?->logo_terciario) }}" id="logo_terciario" placeholder="Logo Terciario">
            {!! $errors->first('logo_terciario', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>

    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
    </div>
</div>