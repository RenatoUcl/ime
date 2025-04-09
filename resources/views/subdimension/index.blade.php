@extends('adminlte::page')
@section('template_title')Subdimensiones
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Subdimensiones</span>
                             <div class="float-right">
                                <a href="{{ route('subdimension.create') }}" class="btn btn-success btn-sm float-right"  data-placement="left">Crear Nuevo</a>
                              </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success m-4">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="card-body bg-white">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead">
                                    <tr>
                                        <th>No</th>
                                        <th>Id Dimension</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($subdimensiones as $subdimensione)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $subdimensione->id_dimension }}</td>
                                            <td>{{ $subdimensione->nombre }}</td>
                                            <td>{!! $subdimensione->descripcion !!}</td>
                                            <td>{{ $subdimensione->estado }}</td>
                                            <td>
                                                <form action="{{ route('subdimension.index', $subdimensione->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('subdimension.show', $subdimensione->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('subdimension.edit', $subdimensione->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Delete') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $subdimensiones->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
