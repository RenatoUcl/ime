<!-- Modal -->
<div class="modal fade" id="ModalA{{ $pregunta->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Alternativa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mt-1">
                    <div class="col-12">
                        <span>{{ $pregunta->id}} | {{ $pregunta->texto }}</span>
                    </div>
                </div>                
                <div class="row mt-1">
                    <div class="col-12">
                        <label for="adepende">Seleccione Dependencia</label>
                        <select name="adepende[{{ $pregunta->id }}]" id="adepende[{{ $pregunta->id }}]" class="form-control">
                            <option value="0">Seleccione Dependencia</option>
                            
                            @foreach ($alternativas as $alters)
                                @if($alters->id_pregunta == $pregunta->id_dependencia && $alters->valor > 1)
                                    <option value="{{ $alters->id }}">{{ $alters->id }} | {{ Str::limit($alters->texto,50,'...') }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row mt-1">
                    <div class="col-12">
                        <label for="alternativa">Alternativa</label>
                        <textarea name="alternativa[{{ $pregunta->id }}]" id="alternativa[{{ $pregunta->id }}]" cols="30" rows="5" placeholder="Ingrese la alternativa" class="form-control"></textarea>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        <label for="valor">Puntaje</label>
                        <input type="text" name="puntaje[{{ $pregunta->id }}]" id="puntaje[{{ $pregunta->id }}]" maxlength="1" class="form-control" >
                    </div>
                </div>
                
            </div>
            <div class="modal-footer">
                <input type="hidden" name="idpreg[{{ $pregunta->id }}]" id="idpreg[{{ $pregunta->id }}]" value="{{ $pregunta->id }}" />
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="action" value="crear_alternativa" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
