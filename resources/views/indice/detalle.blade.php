<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let radarChart;

    function CargaGrafico(){
        const valSubdimensiones = @json($valSubdimensiones);

        console.log(valSubdimensiones);

        let subdimensiones = [];
        let valores = [];
        var promedio = parseFloat(0);

        valSubdimensiones.forEach(sd => {                
            encuestaAct = sd.id_encuesta;
            subdimensiones.push(sd.sdNombre);
            valores.push(sd.promedio);
            promedio += parseFloat(sd.promedio);
        });

        console.log(subdimensiones);
        console.log(valores);

        // Insertar canvas con diseño mejorado
        const contenedor = document.getElementById('contenedor-grafico');
        contenedor.innerHTML = `
            <div style="position: relative; height: 420px; width: 100%; padding: 10px;">
                <canvas id="radarCanvas"></canvas>
            </div>`;
        const ctx = document.getElementById('radarCanvas').getContext('2d');
        
        if (radarChart) {
            radarChart.destroy();
        }

        // Gradiente profesional
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.05)');

        // Crear el gráfico con diseño profesional
        radarChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: subdimensiones,
                datasets: [{
                    label: 'Promedio por Subdimensión',
                    data: valores,
                    backgroundColor: gradient,
                    borderColor: '#10b981',
                    borderWidth: 3,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 8,
                    pointHoverRadius: 12,
                    pointHoverBorderWidth: 3,
                    pointStyle: 'circle',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                        labels: {
                            font: { 
                                size: 14, 
                                weight: 'bold',
                                family: "'Segoe UI', Arial, sans-serif"
                            },
                            padding: 25,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            boxWidth: 15
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.9)',
                        titleFont: { 
                            size: 14, 
                            weight: 'bold',
                            family: "'Segoe UI', Arial, sans-serif"
                        },
                        bodyFont: { 
                            size: 13,
                            family: "'Segoe UI', Arial, sans-serif"
                        },
                        padding: 15,
                        cornerRadius: 12,
                        displayColors: true,
                        boxPadding: 8,
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
                                const emoji = value < 5 ? '🔴' : value < 7.5 ? '🟡' : '🟢';
                                const estado = value < 5 ? 'Alerta' : value < 7.5 ? 'Mínimo Funcional' : 'Máxima Efectividad';
                                return `${emoji} ${estado}`;
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
                            font: {
                                size: 12,
                                weight: 'bold',
                                family: "'Segoe UI', Arial, sans-serif"
                            },
                            color: '#6b7280'
                        },
                        grid: {
                            circular: true,
                            color: function(context) {
                                const value = context.tick.value;
                                if (value === 0) return 'rgba(0,0,0,0.06)';
                                if (value <= 5) return 'rgba(239, 68, 68, 0.15)';
                                if (value <= 7.5) return 'rgba(245, 158, 11, 0.15)';
                                return 'rgba(34, 197, 94, 0.15)';
                            },
                            lineWidth: 1.5
                        },
                        angleLines: {
                            color: 'rgba(0,0,0,0.08)',
                            lineWidth: 2
                        },
                        pointLabels: {
                            font: { 
                                size: 13,
                                weight: 'bold',
                                family: "'Segoe UI', Arial, sans-serif"
                            },
                            color: '#1f2937',
                            padding: 20
                        }
                    }
                },
                animation: {
                    duration: 1200,
                    easing: 'easeInOutCubic'
                }
            }
        });

        // ========================================
        // TABLA MEJORADA CON DISEÑO PROFESIONAL
        // ========================================
        tabla = document.getElementById('contenedor-tabla');
        tabla.innerHTML = "";
        let tablaHTML = `
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead class="bg-primary text-white">
                    <tr>
                        <th><i class="fas fa-tag"></i> Subdimensión</th>
                        <th><i class="fas fa-chart-line"></i> Promedio</th>
                        <th><i class="fas fa-bullseye"></i> Efectividad</th>
                    </tr>
                </thead>
                <tbody>`;
        
        valSubdimensiones.forEach(d => {
            const valor = parseFloat(d.promedio);
            let color, estado, icono, badgeColor, barColor;
            
            if (valor < 5) {
                color = "table-danger";
                estado = "Alerta";
                icono = "🔴";
                badgeColor = "danger";
                barColor = "bg-danger";
            } else if (valor < 7.5) {
                color = "table-warning";
                estado = "Mínimo Funcional";
                icono = "🟡";
                badgeColor = "warning";
                barColor = "bg-warning";
            } else {
                color = "table-success";
                estado = "Máxima Efectividad";
                icono = "🟢";
                badgeColor = "success";
                barColor = "bg-success";
            }

            const barWidth = Math.min((valor / 10) * 100, 100);

            tablaHTML += `
                <tr class="${color}">
                    <td><strong>${d.sdNombre}</strong></td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="badge badge-${badgeColor} mr-2" style="font-size: 1.1rem; min-width: 50px; padding: 0.5rem;">
                                ${valor.toFixed(2)}
                            </span>
                            <div class="progress flex-grow-1" style="height: 10px; border-radius: 10px; background: #e9ecef;">
                                <div class="progress-bar ${barColor}" role="progressbar" 
                                     style="width: ${barWidth}%; border-radius: 10px; transition: width 1.5s ease;" 
                                     aria-valuenow="${valor}" aria-valuemin="0" aria-valuemax="10">
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size: 1.3rem;">${icono}</span>
                        <span class="badge badge-${badgeColor} badge-pill" style="font-size: 0.85rem; padding: 0.4rem 0.8rem;">${estado}</span>
                    </td>
                </tr>
            `;
        });
        tablaHTML += '</tbody></table></div>';
        tabla.innerHTML = tablaHTML;

        // ========================================
        // RESUMEN CON DISEÑO PROFESIONAL
        // ========================================
        const promedioGeneral = (promedio / valSubdimensiones.length).toFixed(2);
        const resumen = document.getElementById('resumen');
        resumen.innerHTML = `
            <div class="row">
                <div class="col-12">
                    <div class="info-box shadow-sm" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <span class="info-box-icon"><i class="fas fa-calculator text-white"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text text-white">Promedio General</span>
                            <span class="info-box-number text-white" style="font-size: 2rem;">${promedioGeneral}</span>
                        </div>
                    </div>
                </div>
            </div>
        `;
    }

    CargaGrafico();
</script>

{{--
@extends('adminlte::page')

@section('template_title')
    Encuestas
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <div class="row mt-5">
            <div class="col-12 mt-3 text-right">
                <a href="#" class="btn btn-primary" onclick="history.back()">Volver</a>
            </div>
            <div class="col-6">
                <div class="card mt-3">
                    <div class="card-header bg-primary">
                        ANALISIS
                    </div>
                    <div class="card-body">
                        <div id="contenedor-grafico" class="mt-5"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card mt-3">
                    <div class="card-header bg-primary">
                        SUBDIMENSIONES
                    </div>
                    <div class="card-body" id="contenedor-tabla">

                    </div>
                    <div class="card-footer" id="resumen">

                    </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let radarChart;

        function CargaGrafico(){
            const valSubdimensiones = @json($valSubdimensiones);

            console.log(valSubdimensiones);

            let subdimensiones = [];
            let valores = [];
            var promedio = parseFloat(0);

            valSubdimensiones.forEach(sd => {                
                encuestaAct = sd.id_encuesta;
                subdimensiones.push(sd.sdNombre);
                valores.push(sd.promedio);
                promedio += parseFloat(sd.promedio);
                console.log(sd.sdNombre);
            });

            console.log(subdimensiones);
            console.log(valores);

            // Insertar dinámicamente el canvas
            const contenedor = document.getElementById('contenedor-grafico');
            contenedor.innerHTML = '<canvas id="radarCanvas"></canvas>';
            const ctx = document.getElementById('radarCanvas').getContext('2d');
            
            // Si ya hay un gráfico, destruirlo para evitar errores
            if (radarChart) {
                radarChart.destroy();
            }

            // Crear el gráfico
            radarChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: subdimensiones,
                    datasets: [{
                        label: 'Promedio por Dimensión',
                        data: valores,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                        pointStyle:'circle',
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        r: {
                            suggestedMin: 0,
                            suggestedMax: 5
                        }
                    }
                }
            });

            // Poblando tabla
            tabla = document.getElementById('contenedor-tabla');
            tabla.innerHTML = "";
            let tablaHTML = `
            <table class="table">
                <thead class="thead bg-primary">
                    <tr>
                    <th scope="col">Dimensión</th>
                    <th scope="col">Promedio</th>
                    <th scope="col">Efectividad</th>
                    <th scope="col"></th>
                    </tr>
                </thead>`;
            var color = "";
            var msje  = "";
            valSubdimensiones.forEach(d => {
                let idDim = d.id_dimension;
                if (d.promedio < 5) {
                    color = "bg-danger";
                    msje  = "Alerta";
                } else if (d.promedio < 7.5){
                    color = "bg-warning";
                    msje  = "Minimo Funcional";
                } else {
                    color = "bg-success";
                    msje  = "Máxima Efectividad";
                }
                tablaHTML += `
                    <tr class="${color}">
                        <td>${d.sdNombre}</td>
                        <td>${d.promedio}</td>
                        <td>` + msje + `</td>
                        <td></td>
                    </tr>
                `;
            });
            tablaHTML += '</tbody></table>';
            tabla.innerHTML = tablaHTML;
            // Fin Poblado tabla

        }

        CargaGrafico();
    </script>
@endsection
--}}
