@extends('adminlte::page')

@section('title', 'Asignar Dimensiones a Usuario')

@section('content_header')
    <h1>Crear Acceso a Encuesta</h1>
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Error!</strong> Debe corregir lo siguiente:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('encuestas.accesos.store') }}" method="POST">
            @csrf

            <div class="card-body">

                <div class="form-group">
                    <label>Encuesta</label>
                    <select name="id_encuesta" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach($encuestas as $e)
                            <option value="{{ $e->id }}">{{ $e->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Usuario</label>
                    <select name="id_usuario" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}">{{ $u->nombre }} {{ $u->ap_paterno }} {{ $u->ap__materno }} || {{ $u->email}}</option>
                        @endforeach
                    </select>
                </div>

                <hr>
                @php
                /*
                <h5>Seleccione las Dimensiones que podrá responder</h5>

                <div class="row">
                    @foreach($dimensiones as $d)
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="dimensiones[]" value="{{ $d->id }}"
                                       class="form-check-input" id="dim_{{ $d->id }}">
                                <label class="form-check-label" for="dim_{{ $d->id }}">
                                    {{ $d->nombre }}
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>
                */
                @endphp
                <div id="contenedor-dimensiones" class="row">
                    <p class="text-muted ml-2">Seleccione primero una encuesta...</p>
                </div>
            </div>

            <div class="card-footer">
                <button class="btn btn-primary">Guardar</button>
                <a href="{{ route('encuestas.accesos.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

@stop

@section('js')
<script>
    document.querySelector('select[name="id_encuesta"]').addEventListener('change', function () {
        let encuestaId = this.value;

        if (!encuestaId) {
            document.getElementById('contenedor-dimensiones').innerHTML = '<p class="text-muted ml-2">Seleccione una encuesta...</p>';
            return;
        }

        fetch(`/encuestas/${encuestaId}/dimensiones`)
            .then(response => response.json())
            .then(data => {

                let contenedor = document.getElementById('contenedor-dimensiones');
                contenedor.innerHTML = '';

                if (data.length === 0) {
                    contenedor.innerHTML = '<p class="text-danger ml-2">Esta encuesta no tiene dimensiones configuradas.</p>';
                    return;
                }

                data.forEach(dim => {
                    contenedor.innerHTML += `
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="dimensiones[]" value="${dim.id}"
                                       class="form-check-input" id="dim_${dim.id}">
                                <label class="form-check-label" for="dim_${dim.id}">
                                    ${dim.nombre}
                                </label>
                            </div>
                        </div>
                    `;
                });
            });
    });
</script>
@stop
