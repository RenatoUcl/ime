@extends('adminlte::page')

@section('template_title')
    Encuestas
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <div class="row mt-5">
            <div class="col-3">
                <div class="card mt-3">
                    <div class="card-header card-primary">
                        ÍNDICE MULTIDIMENSIONAL DE EFECTIVIDAD
                    </div>
                    <div class="card-body">
                        <label for="linea">Línea Programática</label>
                        <select name="linea" id="linea" class="form-control" onchange="VerificaEncuesta();">
                            <option value="X">Seleccione Línea</option>
                            <option value="0">Todas las Líneas</option>
                            @foreach($lineas as $linea)
                                <option value="{{ $linea->id}}">{{ $linea->nombre}}</option>
                            @endforeach
                        </select>

                        <label for="equipo">Encuesta</label>
                        <select name="equipo" id="equipo" class="form-control">
                            <option value="X">Seleccione Encuesta</option>
                            <option value="0">Todas las Encuestas</option>
                        </select>

                    </div>
                </div>
            </div>
            <div class="col">

            </div>
        </div>
    </div>

    <script>
        function VerificaEncuesta(){
            var linea = document.getElementById('linea').value;
            const encuestas = @json($encuestas);
            const select = document.getElementById('equipo');
            encuestas.forEach(encuesta => {
                const option = document.createElement('option');
                option.value = encuesta.id;
                option.textContent = encuesta.nombre;
                select.appendChild(option);
            });
        }        
    </script>

@endsection
