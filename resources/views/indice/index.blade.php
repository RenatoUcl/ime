@extends('adminlte::page')

@section('template_title')
    Encuestas
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <div class="row mt-5">
            <div class="col-6">
                <div class="card mt-3">
                    <div class="card-header bg-primary">
                        ÍNDICE MULTIDIMENSIONAL DE EFECTIVIDAD
                    </div>
                    <div class="card-body">
                        <label for="linea">Línea Programática</label>
                        <select name="linea" id="linea" class="form-control" onchange="VerificaEncuesta();">
                            <option value="0">Seleccione Línea</option>
                            @foreach($lineas as $linea)
                                <option value="{{ $linea->id}}">{{ $linea->nombre}}</option>
                            @endforeach
                        </select>
                        <label for="equipo">Encuesta</label>
                        <select name="equipo" id="equipo" class="form-control" onchange="CargaGrafico();">
                            <option value="0">Seleccione Encuesta</option>
                        </select>
                        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                        <div id="contenedor-grafico" class="mt-5"></div>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card mt-3">
                    <div class="card-header bg-primary">
                        DIMENSIONES
                    </div>
                    <div class="card-body" id="contenedor-tabla"></div>
            </div>
        </div>
    </div>

    <script>
        let radarChart;

        function VerificaEncuesta(){
            var linea = document.getElementById('linea').value;
            const encuestas = @json($encuestas);
            const select = document.getElementById('equipo');
            select.innerHTML = '';
            const optionx = document.createElement('option');
            optionx.value = '0';
            optionx.textContent = 'Seleccione Encuesta';
            select.appendChild(optionx);
            encuestas.forEach(encuesta => {
                const option = document.createElement('option');
                option.value = encuesta.id;
                option.textContent = encuesta.nombre;
                select.appendChild(option);
            });
        }
        function CargaGrafico(){
            var encuesta = document.getElementById('equipo').value;
            const valDimensiones = @json($valDimensiones);
            const valSubdimensiones = @json($valSubdimensiones);
            const valPreguntas = @json($valPreguntas);

            let dimensiones = [];
            let valores = [];
            let encuestaAct = "";
            valDimensiones.forEach(valDimensiones => {
                if(valDimensiones.id_encuesta==encuesta){
                    encuestaAct = valDimensiones.id_encuesta;
                    dimensiones.push(valDimensiones.nombre);
                    valores.push(valDimensiones.promedio);
                }
            });

            tabla = document.getElementById('contenedor-tabla');
            tabla.innerHTML = "";

            console.log("paso");

            console.log("valdimension.idencuesta = " + encuestaAct);
            console.log("encuesta = " + encuesta);
            if(encuestaAct==encuesta){
                let tablaHTML = `
                <table class="table">
                    <thead class="thead bg-primary">
                        <tr>
                        <th scope="col">Dimensión</th>
                        <th scope="col">Promedio</th>
                        <th scope="col">Efectividad</th>
                        <th scope="col">Acciones</th>
                        </tr>
                    </thead>`;
                var color = "";
                var msje  = "";
                valDimensiones.forEach(d => {
                    if(d.id_encuesta==encuesta){
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
                        <tr class="` + color + `">
                            <td>${d.nombre}</td>
                            <td>${d.promedio}</td>
                            <td>` + msje + `</td>
                            <td></td>
                        </tr>
                    `;
                    }
                });

                tablaHTML += '</tbody></table>';
                tabla.innerHTML = tablaHTML;
            }
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
                    labels: dimensiones,
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
        }
    </script>
@endsection
