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
                    msje  = "Critico";
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
