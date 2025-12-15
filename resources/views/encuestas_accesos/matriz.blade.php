@extends('adminlte::page')

@section('title', 'Matriz de Accesos')

@section('content_header')
    <h1>Matriz Usuario × Dimensiones</h1>
@stop

@section('content')

<div class="card">
    <div class="card-body table-responsive">

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Usuario</th>
                    @foreach($dimensiones as $d)
                        <th class="text-center">{{ $d->nombre }}</th>
                    @endforeach
                </tr>
            </thead>

            <tbody>
                @foreach($usuarios as $u)
                <tr>
                    <td>{{ $u->name }}</td>

                    @foreach($dimensiones as $d)
                        @php
                            $tiene = $accesos->where('id_usuario', $u->id)
                                             ->where('id_dimension', $d->id)
                                             ->count() > 0;
                        @endphp

                        <td class="text-center">
                            @if($tiene)
                                <span class="badge badge-success">✔</span>
                            @else
                                <span class="badge badge-secondary">—</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>

        </table>

    </div>
</div>

@stop
