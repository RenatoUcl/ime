@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($subdimensiones->pluck('subdimension'));
    const valores = @json($subdimensiones->pluck('promedio'));

    const ctx = document.getElementById('radarSubdimensiones').getContext('2d');

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [{
                label: '📊 Promedio por Subdimensión',
                data: valores,
                backgroundColor: 'rgba(54, 162, 235, 0.15)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 3,
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 6,
                pointHoverRadius: 9,
                pointStyle: 'circle',
                fill: true,
                tension: 0.2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
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
                            if (value < 5) return '🔴 Crítico';
                            if (value < 7.5) return '🟡 En desarrollo';
                            return '🟢 Óptimo';
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
</script>
@stop

{{-- 

@extends('adminlte::page')

@section('title', 'Detalle por Dimensión')

@section('content_header')
    <h1>Detalle por Dimensión</h1>
@stop

@section('content')
<div class="container-fluid mt-4">

    <div class="row mb-3">
        <div class="col-12 text-right">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                ← Volver
            </a>
        </div>
    </div>

    <div class="row">

        {{-- RADAR --}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Radar Subdimensiones
                </div>
                <div class="card-body">
                    <canvas id="radarSubdimensiones"></canvas>
                </div>
            </div>
        </div>

        {{-- TABLA --}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Resultados
                </div>
                <div class="card-body">
                    <table class="table table-bordered">
                        <thead class="bg-light">
                            <tr>
                                <th>Subdimensión</th>
                                <th>Promedio</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subdimensiones as $sd)
                                @php
                                    if ($sd->promedio < 5) {
                                        $estado = 'Crítico';
                                        $clase = 'table-danger';
                                    } elseif ($sd->promedio < 7.5) {
                                        $estado = 'En desarrollo';
                                        $clase = 'table-warning';
                                    } else {
                                        $estado = 'Óptimo';
                                        $clase = 'table-success';
                                    }
                                @endphp
                                <tr class="{{ $clase }}">
                                    <td>{{ $sd->subdimension }}</td>
                                    <td>{{ number_format($sd->promedio, 2) }}</td>
                                    <td>{{ $estado }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = @json($subdimensiones->pluck('subdimension'));
    const valores = @json($subdimensiones->pluck('promedio'));

    const ctx = document.getElementById('radarSubdimensiones').getContext('2d');

    new Chart(ctx, {
        type: 'radar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Promedio por Subdimensión',
                data: valores,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                pointBackgroundColor: 'rgba(54, 162, 235, 1)',
                pointStyle: 'circle'
            }]
        },
        options: {
            responsive: true,
            scales: {
                r: {
                    suggestedMin: 0,
                    suggestedMax: 10
                }
            }
        }
    });
</script>
@stop --}}