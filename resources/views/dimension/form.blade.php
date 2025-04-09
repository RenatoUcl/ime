<div class="row padding-1 p-1">
    <div class="col-md-12">        
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre', $dimension?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">Descripcion</label>
            <textarea id="summernote" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror">
                {{ old('descripcion', $dimension?->descripcion) }}
            </textarea>
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <input type="hidden" name="estado" value="1" id="estado" />
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" class="btn btn-primary">Guardar</button>
    </div>
</div>