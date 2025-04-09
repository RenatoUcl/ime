<!-- Modal -->
<div class="modal fade" id="Modal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Cabecera</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-12">
                        <label for="ctipo">Tipo Pregunta</label>
                        <select name="ctipo" id="ctipo" class="form-control" onchange="activa_pregunta()">
                            <option value="0">Seleccione tipo de pregunta</option>
                            <option value="1">Alternativas / Selección</option>
                            <option value="2">Responder / Completar</option>
                        </select>
                    </div>
                    <div class="col-12 mt-2" id="dpreg">
                        <label for="cpregunta" visibility="false">Pregunta</label>
                        <textarea name="cpregunta" id="cpregunta" cols="30" rows="4" class="form-control" style=""></textarea>
                    </div>
                    <div class="col-12 mt-2" id="dalter" style="display: none;">
                        <label for="alter[]">Descripción de la alternativa</label>
                        <button type="button" id="btn_agregar" class="btn btn-sm btn-primary mb-3 text-right"> Agregar </button>
                        <input type="text" id="input_nombre" class="form-control mb-3" placeholder="Ingresar la alternativa" />
                        <ul id="ul_lista">
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" name="action" value="crear_cabecera" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
