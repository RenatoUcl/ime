@extends('adminlte::page')

@section('template_title')
    Configuracions
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Configuracions') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('configuracions.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Institucion</th>
									<th >Descripcion</th>
									<th >Objetivos</th>
									<th >Color 1</th>
									<th >Color 2</th>
									<th >Color 3</th>
									<th >Color 4</th>
									<th >Color 5</th>
									<th >Color 6</th>
									<th >Color 7</th>
									<th >Color 8</th>
									<th >Color 9</th>
									<th >Color 10</th>
									<th >Icono</th>
									<th >Logo Principal</th>
									<th >Logo Secundario</th>
									<th >Logo Terciario</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($configuracions as $configuracion)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $configuracion->institucion }}</td>
										<td >{{ $configuracion->descripcion }}</td>
										<td >{{ $configuracion->objetivos }}</td>
										<td >{{ $configuracion->color_1 }}</td>
										<td >{{ $configuracion->color_2 }}</td>
										<td >{{ $configuracion->color_3 }}</td>
										<td >{{ $configuracion->color_4 }}</td>
										<td >{{ $configuracion->color_5 }}</td>
										<td >{{ $configuracion->color_6 }}</td>
										<td >{{ $configuracion->color_7 }}</td>
										<td >{{ $configuracion->color_8 }}</td>
										<td >{{ $configuracion->color_9 }}</td>
										<td >{{ $configuracion->color_10 }}</td>
										<td >{{ $configuracion->icono }}</td>
										<td >{{ $configuracion->logo_principal }}</td>
										<td >{{ $configuracion->logo_secundario }}</td>
										<td >{{ $configuracion->logo_terciario }}</td>

                                            <td>
                                                <form action="{{ route('configuracions.destroy', $configuracion->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('configuracions.show', $configuracion->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('configuracions.edit', $configuracion->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $configuracions->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
