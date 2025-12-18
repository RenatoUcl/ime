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

        {{-- RADAR --}}
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

        {{-- TABLA --}}
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
@stop