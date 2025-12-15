@extends('adminlte::page')

@section('title', 'Editar Acceso')

@section('content_header')
    <h1>Editar Acceso de Usuario a Encuesta</h1>
@endsection

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>¡Error!</strong> Debe corregir lo siguiente:
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="card">
        <form action="{{ route('encuestas.accesos.update', $registro->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label>Encuesta</label>
                    <select name="id_encuesta" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach($encuestas as $e)
                            <option value="{{ $e->id }}"
                                {{ $e->id == $registro->id_encuesta ? 'selected' : '' }}>
                                {{ $e->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label>Usuario</label>
                    <select name="id_usuario" class="form-control" required>
                        <option value="">Seleccione...</option>
                        @foreach($usuarios as $u)
                            <option value="{{ $u->id }}"
                                {{ $u->id == $registro->id_usuario ? 'selected' : '' }}>
                                {{ $u->nombre }} {{ $u->ap_paterno }} {{ $u->ap__materno }} || {{ $u->email}}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div id="contenedor-dimensiones" class="row">
                    <p class="text-muted ml-2">Cargando dimensiones...</p>
                </div>
                <hr>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary">Actualizar</button>
                <a href="{{ route('encuestas.accesos.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>
@endsection

@section('js')
<script>
    let encuestaSelect = document.querySelector('select[name="id_encuesta"]');
    let usuarioId = "{{ $registro->id_usuario }}";
    let encuestaIdSeleccionada = encuestaSelect.value;
    let dimensionesSeleccionadas = @json($dimensionesSeleccionadas);

    function cargarDimensiones(encuestaId) {
        fetch(`/encuestas/${encuestaId}/dimensiones`)
            .then(response => response.json())
            .then(data => {
                let contenedor = document.getElementById('contenedor-dimensiones');
                contenedor.innerHTML = '';
                if (data.length === 0) {
                    contenedor.innerHTML = '<p class="text-danger ml-2">No hay dimensiones disponibles para esta encuesta.</p>';
                    return;
                }
                data.forEach(dim => {
                    let checked = dimensionesSeleccionadas.includes(dim.id) ? 'checked' : '';
                    contenedor.innerHTML += `
                        <div class="col-md-4">
                            <div class="form-check">
                                <input type="checkbox" name="dimensiones[]" value="${dim.id}"
                                       class="form-check-input" id="dim_${dim.id}" ${checked}>
                                <label class="form-check-label" for="dim_${dim.id}">
                                    lalalala ${dim.nombre}
                                </label>
                            </div>
                        </div>
                    `;
                });
            });
    }

    // Cargar dimensiones cuando se abre la página
    cargarDimensiones(encuestaIdSeleccionada);

    // Cargar dinámicamente cuando se cambie la encuesta
    encuestaSelect.addEventListener('change', function () {
        cargarDimensiones(this.value);
    });

</script>
@stop
