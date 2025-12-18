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
                <h5 class="modal-title" id="tituloDimension"></h5>
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
document.addEventListener('DOMContentLoaded', function () {

    let radarChart = null;
    let radarDimension = null;

    const encuestas = @json($encuestas);

    // IMPORTANTE: aquí NO filtramos periodos; usamos lo que venga del backend.
    // Optimizamos: normalizamos strings y ordenamos de forma consistente.
    const periodosRaw = @json($periodos);

    // Normalización / orden (si existe fecha_hasta, la usamos; si no, por id desc)
    const periodos = (Array.isArray(periodosRaw) ? periodosRaw : [])
        .map(p => ({
            ...p,
            id: String(p.id),
            id_encuesta: String(p.id_encuesta),
            estado: (p.estado === true || p.estado === 1 || p.estado === "1") ? 1 : 0,
            fecha_desde: p.fecha_desde || null,
            fecha_hasta: p.fecha_hasta || null,
            nombre_periodo: p.nombre_periodo || ''
        }))
        .sort((a, b) => {
            // Orden preferido: fecha_hasta desc (si existe), si no id desc
            if (a.fecha_hasta && b.fecha_hasta) {
                if (a.fecha_hasta > b.fecha_hasta) return -1;
                if (a.fecha_hasta < b.fecha_hasta) return 1;
            }
            // fallback id desc
            return parseInt(b.id, 10) - parseInt(a.id, 10);
        });

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
            .filter(e => String(e.id_linea) === String(lineaId))
            .forEach(e => {
                elEncuesta.innerHTML += `<option value="${e.id}">${e.nombre}</option>`;
            });

        elEncuesta.disabled = false;
    });

    /* =========================
       ENCUESTA → PERIODOS (A y B)
       REGLA: Mostrar TODOS (activos e inactivos).
       Solo mostrar comparación si existen 2+ periodos.
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

        const periodosEncuesta = periodos.filter(p => p.id_encuesta === String(encuestaId));

        // Periodo A (TODOS)
        periodosEncuesta.forEach(p => {
            const tagEstado = p.estado === 1 ? ' (Activo)' : ' (Inactivo)';
            const rango = (p.fecha_desde && p.fecha_hasta) ? ` [${p.fecha_desde} → ${p.fecha_hasta}]` : '';
            elPeriodo.innerHTML += `<option value="${p.id}">${p.nombre_periodo}${tagEstado}${rango}</option>`;
        });
        elPeriodo.disabled = periodosEncuesta.length === 0;

        // Periodo B solo si hay 2 o más periodos
        if (periodosEncuesta.length >= 2) {
            elBloqueComparacion.style.display = 'block';
            elPeriodoB.innerHTML = '<option value="">Sin comparación</option>';
            periodosEncuesta.forEach(p => {
                const tagEstado = p.estado === 1 ? ' (Activo)' : ' (Inactivo)';
                const rango = (p.fecha_desde && p.fecha_hasta) ? ` [${p.fecha_desde} → ${p.fecha_hasta}]` : '';
                elPeriodoB.innerHTML += `<option value="${p.id}">${p.nombre_periodo}${tagEstado}${rango}</option>`;
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

        fetchResultados(encuestaId, periodoAId)
            .then(dataA => {

                if (!periodoBId) {
                    renderResultados(dataA, null, encuestaId, periodoAId);
                    cargarSemaforo(encuestaId, periodoAId);
                    return;
                }

                fetchResultados(encuestaId, periodoBId)
                    .then(dataB => {
                        renderResultados(dataA, dataB, encuestaId, periodoAId);
                        cargarSemaforo(encuestaId, periodoAId);
                    });
            })
            .catch(err => {
                console.error('Error cargando resultados:', err);
                document.getElementById('contenedor-tabla').innerHTML =
                    '<div class="alert alert-danger">Error cargando resultados. Revisa consola.</div>';
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

        const labels = dataA.map(d => d.nombre);
        const valoresA = dataA.map(d => parseFloat(d.promedio));

        let promedioA = 0;
        dataA.forEach(d => promedioA += parseFloat(d.promedio));
        promedioA = promedioA / dataA.length;

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
                        <button type="button" class="btn btn-sm btn-info"
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

        const datasets = [];

        datasets.push({
            label: 'Período A',
            data: valoresA,
            backgroundColor: 'rgba(54,162,235,0.2)',
            borderColor: 'rgba(54,162,235,1)'
        });

        if (dataB && dataB.length) {
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

        document.getElementById('contenedor-grafico').innerHTML =
            '<canvas id="radarCanvas"></canvas>';

        const ctx = document.getElementById('radarCanvas').getContext('2d');
        if (radarChart) radarChart.destroy();

        radarChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: datasets.map(ds => ({
                    ...ds,
                    borderWidth: 2,
                    pointRadius: 3,
                    pointHoverRadius: 4,
                    fill: true
                }))
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 12 }
                        }
                    }
                },
                scales: {
                    r: {
                        min: 0,
                        max: 10,                     // ← escala real de subdimensión
                        ticks: {
                            display: false           // ← sin números
                        },
                        grid: {
                            circular: true,          // ← anillos suaves
                            color: '#e5e5e5'
                        },
                        angleLines: {
                            color: '#e0e0e0'
                        },
                        pointLabels: {
                            font: { size: 12 },
                            color: '#333'
                        }
                    }
                }
            }
        });
    }

    /* =========================
       VER DIMENSION (MODAL)
       Debe quedar en window para onclick inline
    =========================*/
    window.verDimension = function (encuestaId, instanciaId, dimensionId) {

        const ruta =
            "{{ route('indice.dimension.ver', ['encuesta'=>':e','dimension'=>':d','instancia'=>':i']) }}"
                .replace(':e', encuestaId)
                .replace(':d', dimensionId)
                .replace(':i', instanciaId);

        fetch(ruta)
            .then(r => r.json())
            .then(resp => {

                // 🔎 DEBUG (puedes quitar luego)
                console.log('Detalle dimensión:', resp);

                if (!resp.subdimensiones || resp.subdimensiones.length === 0) {
                    alert('No hay datos para esta dimensión.');
                    return;
                }

                // Título dinámico
                document.querySelector('#modalDimension .modal-title')
                    .innerText = 'Detalle – ' + resp.dimension;

                const labels = resp.subdimensiones.map(s => s.nombre);
                const valores = resp.subdimensiones.map(s => parseFloat(s.valor));

                const ctx = document.getElementById('radarDimension').getContext('2d');

                if (radarDimension) radarDimension.destroy();

                radarDimension = new Chart(ctx, {
                    type: 'radar',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Resultado por Subdimensión',
                            data: valores,
                            backgroundColor: 'rgba(54,162,235,0.25)',
                            borderColor: 'rgba(54,162,235,1)',
                            pointBackgroundColor: 'rgba(54,162,235,1)'
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            r: {
                                min: 0,
                                max: 10,
                                ticks: { stepSize: 2 }
                            }
                        }
                    }
                });

                $('#modalDimension').modal('show');
            })
            .catch(err => {
                console.error('Error verDimension:', err);
                alert('Error cargando detalle de dimensión');
            });
    };

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
            })
            .catch(err => {
                console.error('Error semáforo:', err);
                document.getElementById('semaforo-global').style.display = 'none';
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

        if (radarChart) {
            radarChart.destroy();
            radarChart = null;
        }
    }

});
</script>
@endsection