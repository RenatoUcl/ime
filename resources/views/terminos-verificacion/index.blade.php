@extends('adminlte::page')
@section('template_title')Subdimensiones
@section('content')

    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Términos de verificación</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{route('terminos-verificacion.create')}}" class="btn btn-primary"><i class="fas fa-plus"></i> Nuevo término</a>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Listado de términos</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Término</th>
                                <th>Subdimensiones</th>
                                <th width="150">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($terminos as $item)
                                <tr>
                                    <td>{{$item->termino}}</td>
                                    <td>
                                        @foreach($item->subdimensiones as $sub)
                                            <span class="badge badge-info">{{$sub->nombre}}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{route( 'terminos-verificacion.edit', $item->id )}}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                        <form style="display:inline" method="POST" action="{{route('terminos-verificacion.destroy', $item->id )}}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

@endsection
