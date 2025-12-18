@extends('adminlte::page')

@section('template_title')
Índice Multidimensional
@endsection

@section('content')
<div class="container-fluid mt-5">
    <div class="row mt-5">

        {{-- COLUMNA IZQUIERDA --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    ÍNDICE MULTIDIMENSIONAL DE EFECTIVIDAD
                </div>

                <div class="card-body">

                    {{-- LINEA PROGRAMATICA --}}
                    <label>Línea Programática</label>
                    <select id="linea" class="form-control mb-3">
                        <option value="">Seleccione Línea</option>
                        @foreach($lineas as $l)
                            <option value="{{ $l->id }}">{{ $l->nombre }}</option>
                        @endforeach
                    </select>

                    {{-- ENCUESTA --}}
                    <label>Encuesta</label>
                    <select id="encuesta" class="form-control mb-3" disabled>
                        <option value="">Seleccione Encuesta</option>
                    </select>

                    {{-- PERIODO A --}}
                    <label>Período de aplicación</label>
                    <select id="periodo" class="form-control mb-3" disabled>
                        <option value="">Seleccione Período</option>
                    </select>

                    {{-- PERIODO B (COMPARACIÓN) - SOLO SI EXISTEN 2+ PERIODOS --}}
                    <div id="bloqueComparacion" style="display:none;">
                        <label>Período B (comparación)</label>
                        <select id="periodoB" class="form-control mb-4" disabled>
                            <option value="">Sin comparación</option>
                        </select>
                    </div>

                    {{-- GRAFICO --}}
                    <div id="contenedor-grafico"></div>

                </div>
            </div>
        </div>

        {{-- COLUMNA DERECHA --}}
        <div class="col-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    DIMENSIONES
                </div>
                <div class="card-body" id="contenedor-tabla"></div>
                <div class="card-footer" id="resumen"></div>
            </div>

            {{-- SEMAFORO GLOBAL --}}
            <div id="semaforo-global" class="mt-4" style="display:none;"></div>
        </div>

    </div>
</div>

{{-- MODAL VER DIMENSIÓN --}}
<div class="modal fade" id="modalDimension" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">Detalle por Subdimensiones</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <canvas id="radarDimension"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let radarChart = null;
let radarDimension = null;

const encuestas = @json($encuestas);
const periodos  = @json($periodos);

const elLinea   = document.getElementById('linea');
const elEncuesta= document.getElementById('encuesta');
const elPeriodo = document.getElementById('periodo');

const elBloqueComparacion = document.getElementById('bloqueComparacion');
const elPeriodoB = document.getElementById('periodoB');

/* =========================
   LINEA → ENCUESTAS
=========================*/
elLinea.addEventListener('change', function () {

    const lineaId = this.value;

    elEncuesta.innerHTML = '<option value="">Seleccione Encuesta</option>';
    elPeriodo.innerHTML  = '<option value="">Seleccione Período</option>';

    // Reset comparacion
    elBloqueComparacion.style.display = 'none';
    elPeriodoB.innerHTML = '<option value="">Sin comparación</option>';
    elPeriodoB.disabled = true;

    elEncuesta.disabled = true;
    elPeriodo.disabled  = true;

    limpiarResultados();

    if (!lineaId) return;

    encuestas
        .filter(e => e.id_linea == lineaId)
        .forEach(e => {
            elEncuesta.innerHTML += `<option value="${e.id}">${e.nombre}</option>`;
        });

    elEncuesta.disabled = false;
});

/* =========================
   ENCUESTA → PERIODOS (A y B)
=========================*/
elEncuesta.addEventListener('change', function () {

    const encuestaId = this.value;

    elPeriodo.innerHTML = '<option value="">Seleccione Período</option>';
    elPeriodo.disabled = true;

    // Reset comparacion
    elBloqueComparacion.style.display = 'none';
    elPeriodoB.innerHTML = '<option value="">Sin comparación</option>';
    elPeriodoB.disabled = true;

    limpiarResultados();

    if (!encuestaId) return;

    const periodosEncuesta = periodos.filter(p => p.id_encuesta == encuestaId);

    // Periodo A
    periodosEncuesta.forEach(p => {
        elPeriodo.innerHTML += `<option value="${p.id}">${p.nombre_periodo}</option>`;
    });
    elPeriodo.disabled = false;

    // Periodo B solo si hay 2 o más periodos
    if (periodosEncuesta.length >= 2) {
        elBloqueComparacion.style.display = 'block';
        elPeriodoB.innerHTML = '<option value="">Sin comparación</option>';
        periodosEncuesta.forEach(p => {
            elPeriodoB.innerHTML += `<option value="${p.id}">${p.nombre_periodo}</option>`;
        });
        elPeriodoB.disabled = false;
    }
});

/* =========================
   PERIODOS → RESULTADOS (A y opcional B)
=========================*/
elPeriodo.addEventListener('change', cargarResultados);
elPeriodoB.addEventListener('change', cargarResultados);

function cargarResultados() {

    const encuestaId = elEncuesta.value;
    const periodoAId = elPeriodo.value;
    const periodoBId = elPeriodoB ? elPeriodoB.value : '';

    limpiarResultados();

    if (!encuestaId || !periodoAId) return;

    // 1) Resultados período A (obligatorio)
    fetchResultados(encuestaId, periodoAId)
        .then(dataA => {

            // 2) Si no hay B, render normal
            if (!periodoBId) {
                renderResultados(dataA, null, encuestaId, periodoAId);
                cargarSemaforo(encuestaId, periodoAId);
                return;
            }

            // 3) Si hay B, render comparado
            fetchResultados(encuestaId, periodoBId)
                .then(dataB => {
                    renderResultados(dataA, dataB, encuestaId, periodoAId);
                    // Semáforo SIEMPRE del A (criterio actual)
                    cargarSemaforo(encuestaId, periodoAId);
                });
        });
}

function fetchResultados(encuestaId, periodoId) {
    return fetch("{{ route('indice.resultados') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            encuesta_id: encuestaId,
            instancia_id: periodoId
        })
    }).then(r => r.json());
}

/* =========================
   RENDER RESULTADOS (A y opcional B)
=========================*/
function renderResultados(dataA, dataB, encuestaId, periodoAId) {

    if (!dataA || !dataA.length) {
        document.getElementById('contenedor-tabla').innerHTML =
            '<div class="alert alert-warning">No hay resultados para este período</div>';
        return;
    }

    // Labels y valores A
    const labels = dataA.map(d => d.nombre);
    const valoresA = dataA.map(d => parseFloat(d.promedio));

    // Promedio general A (se mantiene criterio original)
    let promedioA = 0;
    dataA.forEach(d => promedioA += parseFloat(d.promedio));
    promedioA = promedioA / dataA.length;

    // TABLA (A) + BOTÓN VER (se mantiene)
    let html = `<table class="table">
        <thead class="bg-primary text-white">
            <tr>
                <th>Dimensión</th>
                <th>Promedio</th>
                <th>Efectividad</th>
                <th></th>
            </tr>
        </thead><tbody>`;

    dataA.forEach(d => {

        let color = d.promedio < 5 ? 'bg-danger'
                    : d.promedio < 7.5 ? 'bg-warning'
                    : 'bg-success';

        let txt = d.promedio < 5 ? 'Alerta'
                  : d.promedio < 7.5 ? 'Mínimo Funcional'
                  : 'Máxima Efectividad';

        html += `
            <tr class="${color}">
                <td>${d.nombre}</td>
                <td>${d.promedio}</td>
                <td>${txt}</td>
                <td>
                    <button class="btn btn-sm btn-info"
                        onclick="verDimension(${encuestaId}, ${periodoAId}, ${d.id_dimension})">
                        VER
                    </button>
                </td>
            </tr>`;
    });

    html += '</tbody></table>';

    document.getElementById('contenedor-tabla').innerHTML = html;
    document.getElementById('resumen').innerHTML =
        `<h4 class="text-center">Promedio General: ${promedioA.toFixed(2)}</h4>`;

    // DATASET(S) RADAR
    const datasets = [];

    datasets.push({
        label: 'Período A',
        data: valoresA,
        backgroundColor: 'rgba(54,162,235,0.2)',
        borderColor: 'rgba(54,162,235,1)'
    });

    if (dataB && dataB.length) {
        // Alinear B por id_dimension para evitar descalces
        const mapB = new Map();
        dataB.forEach(x => mapB.set(String(x.id_dimension), parseFloat(x.promedio)));

        const valoresB = dataA.map(d => {
            const key = String(d.id_dimension);
            return mapB.has(key) ? mapB.get(key) : null;
        });

        datasets.push({
            label: 'Período B',
            data: valoresB,
            backgroundColor: 'rgba(255,99,132,0.2)',
            borderColor: 'rgba(255,99,132,1)'
        });
    }

    // GRAFICO PRINCIPAL
    document.getElementById('contenedor-grafico').innerHTML =
        '<canvas id="radarCanvas"></canvas>';

    const ctx = document.getElementById('radarCanvas').getContext('2d');
    if (radarChart) radarChart.destroy();

    radarChart = new Chart(ctx, {
        type: 'radar',
        data: { labels, datasets },
        options: {
            scales: { r: { suggestedMin: 0, suggestedMax: 5 } }
        }
    });
}

/* =========================
   VER DIMENSION (MODAL)
=========================*/
function verDimension(encuestaId, instanciaId, dimensionId) {

    const ruta =
        "{{ route('indice.dimension.ver', ['encuesta'=>':e','instancia'=>':i','dimension'=>':d']) }}"
            .replace(':e', encuestaId)
            .replace(':i', instanciaId)
            .replace(':d', dimensionId);

    fetch(ruta)
        .then(r => r.json())
        .then(data => {

            let labels = [];
            let valores = [];

            data.forEach(d => {
                labels.push(d.nombre);
                valores.push(d.promedio);
            });

            const ctx = document.getElementById('radarDimension').getContext('2d');
            if (radarDimension) radarDimension.destroy();

            radarDimension = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Promedio por Subdimensión',
                        data: valores,
                        backgroundColor: 'rgba(255,99,132,0.2)',
                        borderColor: 'rgba(255,99,132,1)'
                    }]
                },
                options: {
                    scales: { r: { suggestedMin: 0, suggestedMax: 5 } }
                }
            });

            $('#modalDimension').modal('show');
        });
}

/* =========================
   SEMAFORO GLOBAL
=========================*/
function cargarSemaforo(encuestaId, periodoId) {

    const rutaSemaforo =
        "{{ route('indice.semaforo', ['encuesta' => ':e', 'instancia' => ':i']) }}"
            .replace(':e', encuestaId)
            .replace(':i', periodoId);

    fetch(rutaSemaforo)
        .then(r => r.json())
        .then(data => {
            document.getElementById('semaforo-global').style.display = 'block';
            document.getElementById('semaforo-global').innerHTML = `
                <div class="small-box ${data.color}">
                    <div class="inner">
                        <h3>${data.promedio}</h3>
                        <p>${data.estado}</p>
                    </div>
                    <div class="icon">
                        <i class="fas ${data.icono}"></i>
                    </div>
                </div>
            `;
        });
}

/* =========================
   LIMPIEZA
=========================*/
function limpiarResultados() {
    document.getElementById('contenedor-grafico').innerHTML = '';
    document.getElementById('contenedor-tabla').innerHTML = '';
    document.getElementById('resumen').innerHTML = '';
    document.getElementById('semaforo-global').style.display = 'none';
}
</script>
@endsection