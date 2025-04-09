@extends('adminlte::page')

@section('template_title')
    Niveles Secundarios
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Niveles Secundarios') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('niveles-secundario.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
                                  {{ __('Create New') }}
                                </a>
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
                                        
									<th >Id Nivel Primario</th>
									<th >Id Rol</th>
									<th >Nombre</th>
									<th >Descripcion</th>
									<th >Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($nivelesSecundarios as $nivelesSecundario)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $nivelesSecundario->id_nivel_primario }}</td>
										<td >{{ $nivelesSecundario->id_rol }}</td>
										<td >{{ $nivelesSecundario->nombre }}</td>
										<td >{{ $nivelesSecundario->descripcion }}</td>
										<td >{{ $nivelesSecundario->estado }}</td>

                                            <td>
                                                <form action="{{ route('niveles-secundarios.destroy', $nivelesSecundario->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('niveles-secundarios.show', $nivelesSecundario->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('niveles-secundarios.edit', $nivelesSecundario->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
                                                    @csrf
                                                    @method('DELETE')
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
                {!! $nivelesSecundarios->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
