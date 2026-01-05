<div class="modal fade"
     id="ModalEditAlt{{ $alter->id }}"
     tabindex="-1"
     role="dialog"
     aria-hidden="true">

    <div class="modal-dialog" role="document">
        <div class="modal-content">

            <div class="modal-header bg-warning">
                <h5 class="modal-title text-white">
                    Editar Alternativa
                </h5>
                <button type="button"
                        class="close text-white"
                        data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <div class="mb-2">
                    <strong>Pregunta:</strong><br>
                    {!! $alter->pregunta?->texto !!}
                </div>

                {{-- DEPENDENCIA --}}
                @if ($alter->pregunta?->tipo == 1)
                    <div class="form-group">
                        <label>Depende de alternativa</label>
                        <select name="edit_adepende[{{ $alter->id }}]"
                                class="form-control">

                            <option value="0">Sin dependencia</option>

                            @foreach ($alternativas as $altDep)
                                @if ($altDep->id_pregunta == $alter->pregunta->id_dependencia)
                                    <option value="{{ $altDep->id }}"
                                        {{ $alter->id_dependencia == $altDep->id ? 'selected' : '' }}>
                                        {{ Str::limit($altDep->texto, 60) }}
                                    </option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                @else
                    <input type="hidden"
                           name="edit_adepende[{{ $alter->id }}]"
                           value="0">
                @endif

                {{-- TEXTO --}}
                <div class="form-group">
                    <label>Texto Alternativa</label>
                    <textarea name="edit_alternativa[{{ $alter->id }}]"
                              rows="4"
                              class="form-control">{{ $alter->texto }}</textarea>
                </div>

                {{-- VALOR --}}
                <div class="form-group">
                    <label>Puntaje</label>
                    <input type="number"
                           name="edit_puntaje[{{ $alter->id }}]"
                           class="form-control"
                           min="0"
                           max="9"
                           value="{{ $alter->valor }}">
                </div>

            </div>

            <div class="modal-footer">
                <input type="hidden"
                       name="edit_id_alternativa[]"
                       value="{{ $alter->id }}">

                <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                    Cancelar
                </button>

                <button type="submit"
                        name="action"
                        value="editar_alternativa"
                        class="btn btn-warning">
                    Guardar Cambios
                </button>
            </div>

        </div>
    </div>
</div>