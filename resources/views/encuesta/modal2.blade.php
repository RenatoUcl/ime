<!-- Modal -->
<div class="modal fade" id="Modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Agregar Pregunta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <label for="id_subdimension">Subdimension de la Pregunta</label>
                        <select name="id_subdimension" class="form-control">
                            <option value="0">Seleccione una Subdimensión</option>
                            @foreach ($subdimensiones as $subdimension)
                                <option value="{{ $subdimension->id }}">{{ $subdimension->posicion}} | {{ $subdimension->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="tipo">Tipo Pregunta</label>
                        <select name="tipo" id="tipo" class="form-control" onchange="activa_dependencia()"> 
                            <option value="0">Seleccione tipo de Pregunta</option>
                            <option value="2">Sin dependencia</option>
                            <option value="1">Con dependencia</option>
                        </select>
                    </div>
                    <div class="col-12" id="depende" style="display: none;">
                        <label for="dependede">Pregunta dependiente</label>
                        <select name="dependede" id="dependede" class="form-control">
                            <option value="0" selected>Seleccione</option>
                            @if ($preguntas)
                                @foreach ($preguntas as $dpnd)
                                        <option value="{{ $dpnd->id }}">{{ $dpnd->id }} | {{ $dpnd->id_subdimension}} | {{ $dpnd->texto }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="posicion">Posición</label>
                        <select name="posicion" id="posicion" class="form-control">
                            <option value="0" selected>Seleccione</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="preguntax">Pregunta</label>
                        <textarea name="preguntax" id="preguntax" cols="30" rows="10" placeholder="Ingrese la Pregunta" class="form-control"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" name="action" value="crear_pregunta" class="btn btn-primary">Guardar</button>
            </div>
        </div>
    </div>
</div>
