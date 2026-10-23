@extends('adminlte::page')

@section('title','Bienvenido al IME')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mt-3">
                <div class="card-header">{{ __('Bienvenido al IME') }} : {{ auth()->user()->nombre ?? 'Usuario' }} {{ auth()->user()->ap_paterno ?? 'Apellido 1' }} {{ auth()->user()->ap_materno ?? 'Apellido 2' }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <table class="table table-striped table-bordered">
                        <thead class="table-primary">
                            <tr colspan="2">
                                <h3>Encuestas Asignadas</h3>
                            </tr>
                            <tr>
                                <th>Nombre de la Encuesta</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($encuestas as $encuesta)
                                <tr>
                                    <td>{{ $encuesta['nombre'] ?? 'Sin nombre' }}</td>
                                    <td>{{ $encuesta['completado'] ? '✅ Completada' : '⏳ Pendiente' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2">No tienes encuestas asignadas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if(auth()->user()->hasRole('admin'))
                        <br><br>
                        <table class="table table-striped table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Nombre de la Encuesta</th>
                                    <th>Total Asignados</th>
                                    <th>Respondieron</th>
                                    <th>Pendientes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($porEncuesta as $encuesta)
                                    <tr>
                                        <td>{{ $encuesta->nombre ?? 'Sin nombre' }}</td>
                                        <td>{{ $encuesta->total_asignados ?? 0 }}</td>
                                        <td>{{ $encuesta->respondieron ?? 0 }}</td>
                                        <td>{{ $encuesta->pendientes ?? 0 }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">No hay encuestas disponibles.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="font-weight-bold">
                                    <td>Total</td>
                                    <td>{{ $totales['total_asignados'] }}</td>
                                    <td>{{ $totales['respondieron'] }}</td>
                                    <td>{{ $totales['pendientes'] }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    @endif
                </div>
                <div class="card-footer text-right border-top">
                    <a href="{{route('logout')}}" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesión</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
