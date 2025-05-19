@extends('adminlte::page')

@section('template_title')
    Encuestas
@endsection

@section('content')
    <div class="container-fluid mt-5">
        <div class="row mt-5">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                        ÍNDICE MULTIDIMENSIONAL DE EFECTIVIDAD
                    </div>
                    <div class="card-body">
                        <label for="linea">Línea Programática</label>
                        <select name="linea" id="linea" class="form-control">
                            <option value="">Seleccione Línea</option>
                            <option value=""></option>
                        </select>

                        <label for="equipo">Equipo</label>
                        <select name="equipo" id="equipo" class="form-control">
                            <option value="">Seleccione Equipo</option>
                            <option value=""></option>
                        </select>

                    </div>
                </div>
            </div>
            <div class="col">

            </div>
        </div>
    </div>
@endsection
