@extends('adminlte::page')

@section('template_title')
Índice Multidimensional
@endsection

@section('content')
<div class="container-fluid">
    <div class="row pt-3">

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
                    DETALLE DE DIMENSIONES
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
    =========================*
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
   RENDER RESULTADOS (A y opcional B) - MEJORADO
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

        // ========================================
        // TABLA MEJORADA CON BARRAS DE PROGRESO
        // ========================================
        let html = `<div class="table-responsive">
            <table class="table table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th><i class="fas fa-tag"></i> Dimensión</th>
                        <th><i class="fas fa-chart-line"></i> Promedio</th>
                        <th><i class="fas fa-bullseye"></i> Efectividad</th>
                        <th><i class="fas fa-cogs"></i> Acción</th>
                    </tr>
                </thead>
                <tbody>`;

        dataA.forEach(d => {
            const valor = parseFloat(d.promedio);
            
            let color, estado, icono, badgeColor, barColor;
            if (valor < 5) {
                color = 'table-danger';
                estado = 'Alerta';
                icono = '🔴';
                badgeColor = 'danger';
                barColor = 'bg-danger';
            } else if (valor < 7.5) {
                color = 'table-warning';
                estado = 'Mínimo Funcional';
                icono = '🟡';
                badgeColor = 'warning';
                barColor = 'bg-warning';
            } else {
                color = 'table-success';
                estado = 'Máxima Efectividad';
                icono = '🟢';
                badgeColor = 'success';
                barColor = 'bg-success';
            }

            const barWidth = Math.min((valor / 10) * 100, 100);

            // Valor B si existe
            let valorB = '';
            let comparacion = '';
            if (dataB && dataB.length) {
                const mapB = new Map();
                dataB.forEach(x => mapB.set(String(x.id_dimension), parseFloat(x.promedio)));
                const key = String(d.id_dimension);
                if (mapB.has(key)) {
                    const valB = mapB.get(key);
                    valorB = `<br><small class="text-muted">B: ${valB.toFixed(2)}</small>`;
                    const diff = (valor - valB);
                    const arrow = diff > 0 ? '↑' : diff < 0 ? '↓' : '→';
                    const colorDiff = diff > 0 ? 'text-success' : diff < 0 ? 'text-danger' : 'text-muted';
                    comparacion = `<br><small class="${colorDiff}">${arrow} ${Math.abs(diff).toFixed(2)}</small>`;
                }
            }

            html += `
                <tr class="${color}">
                    <td><strong>${d.nombre}</strong></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <center>
                                <span class="badge badge-${badgeColor} mr-2" style="font-size: 1rem; min-width: 45px;">${valor.toFixed(2)}</span>
                            </center>
                            </div>
                        </div>
                        ${valorB}
                        ${comparacion}
                    </td>
                    <td>
                        <span class="badge badge-${badgeColor}">${estado}</span>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info btn-rounded"
                            onclick="verDimension(${encuestaId}, ${periodoAId}, ${d.id_dimension})">
                            <i class="fas fa-chart-pie"></i> Ver
                        </button>
                    </td>
                </tr>`;
        });

        html += '</tbody></table></div>';
        document.getElementById('contenedor-tabla').innerHTML = html;

        // ========================================
        // RESUMEN MEJORADO
        // ========================================
        let resumenHtml = `
            <div class="row">
                <div class="col-12">
                    <div class="info-box bg-gradient-primary">
                        <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Promedio General Período A</span>
                            <span class="info-box-number">${promedioA.toFixed(2)}</span>
                        </div>
                    </div>
                </div>`;

        if (dataB && dataB.length) {
            const mapB = new Map();
            dataB.forEach(x => mapB.set(String(x.id_dimension), parseFloat(x.promedio)));
            let promedioB = 0;
            let countB = 0;
            dataA.forEach(d => {
                const key = String(d.id_dimension);
                if (mapB.has(key)) {
                    promedioB += mapB.get(key);
                    countB++;
                }
            });
            promedioB = countB > 0 ? promedioB / countB : 0;
            const diffGlobal = (promedioA - promedioB);
            const colorGlobal = diffGlobal > 0 ? 'success' : diffGlobal < 0 ? 'danger' : 'secondary';
            const iconGlobal = diffGlobal > 0 ? 'fa-arrow-up' : diffGlobal < 0 ? 'fa-arrow-down' : 'fa-minus';

            resumenHtml += `
                <div class="col-12 mt-2">
                    <div class="info-box bg-gradient-info">
                        <span class="info-box-icon"><i class="fas ${iconGlobal}"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Promedio General Período B</span>
                            <span class="info-box-number">${promedioB.toFixed(2)}</span>
                            <span class="text-${colorGlobal}" style="font-size: 0.9rem;">
                                ${diffGlobal > 0 ? '+' : ''}${diffGlobal.toFixed(2)}
                            </span>
                        </div>
                    </div>
                </div>`;
        }

        resumenHtml += '</div>';
        document.getElementById('resumen').innerHTML = resumenHtml;

        // ========================================
        // GRÁFICO RADAR MEJORADO
        // ========================================
        document.getElementById('contenedor-grafico').innerHTML =
            '<div style="position: relative; height: 400px; width: 100%;">' +
            '<canvas id="radarCanvas"></canvas></div>';

        const ctx = document.getElementById('radarCanvas').getContext('2d');
        if (radarChart) radarChart.destroy();

        const datasets = [];

        // Dataset Período A
        datasets.push({
            label: '📊 Período A',
            data: valoresA,
            backgroundColor: 'rgba(54, 162, 235, 0.15)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 3,
            pointBackgroundColor: 'rgba(54, 162, 235, 1)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6,
            pointHoverRadius: 9,
            fill: true,
            tension: 0.2
        });

        // Dataset Período B
        if (dataB && dataB.length) {
            const mapB = new Map();
            dataB.forEach(x => mapB.set(String(x.id_dimension), parseFloat(x.promedio)));

            const valoresB = dataA.map(d => {
                const key = String(d.id_dimension);
                return mapB.has(key) ? mapB.get(key) : null;
            });

            datasets.push({
                label: '📈 Período B',
                data: valoresB,
                backgroundColor: 'rgba(255, 99, 132, 0.15)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 3,
                borderDash: [6, 4],
                pointBackgroundColor: 'rgba(255, 99, 132, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 9,
                fill: true,
                tension: 0.2,
                pointStyle: 'rectRot'
            });
        }

        radarChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { size: 13, weight: 'bold' },
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleFont: { size: 14, weight: 'bold' },
                        bodyFont: { size: 13 },
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: true,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                let value = context.parsed.r;
                                if (value !== null && value !== undefined) {
                                    label += ': ' + value.toFixed(2);
                                }
                                return label;
                            },
                            afterBody: function(tooltipItems) {
                                const value = tooltipItems[0].parsed.r;
                                if (value === null || value === undefined) return '';
                                if (value < 5) return '⚠️ Alerta';
                                if (value < 7.5) return '📊 Mínimo Funcional';
                                return '✅ Máxima Efectividad';
                            }
                        }
                    }
                },
                scales: {
                    r: {
                        min: 0,
                        max: 10,
                        ticks: {
                            stepSize: 2,
                            backdropColor: 'transparent',
                            font: { size: 11, weight: 'bold' },
                            color: '#666'
                        },
                        grid: {
                            circular: true,
                            color: function(context) {
                                const value = context.tick.value;
                                if (value === 0) return 'rgba(0,0,0,0.08)';
                                if (value <= 5) return 'rgba(220, 53, 69, 0.15)';
                                if (value <= 7.5) return 'rgba(255, 193, 7, 0.15)';
                                return 'rgba(40, 167, 69, 0.15)';
                            },
                            lineWidth: 1.5
                        },
                        angleLines: {
                            color: 'rgba(0,0,0,0.08)',
                            lineWidth: 1.5
                        },
                        pointLabels: {
                            font: { size: 12, weight: 'bold' },
                            color: '#333',
                            padding: 15
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
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