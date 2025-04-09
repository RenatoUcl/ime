@extends('adminlte::page')

@section('title','Departamentos')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">Departamentos</span>
                             <div class="float-right">
                                <a href="{{ route('departamento.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">Nuevo Departamento</a>
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
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($departamentos as $item)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $item->nombre }}</td>
										<td >{{ $item->descripcion }}</td>
										<td >{{ $item->estado }}</td>

                                            <td>
                                                <form action="{{ route('departamento.index', $item->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('departamento.show', $item->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Ver') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('departamento.edit', $item->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Editar') }}</a>
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i> {{ __('Eliminar') }}</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $departamentos->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
