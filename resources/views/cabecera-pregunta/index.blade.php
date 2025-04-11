@extends('adminlte::page')

@section('template_title')
    Cabecera Preguntas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Cabecera Preguntas') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('cabecera-pregunta.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Id Encuesta</th>
									<th >Tipo</th>
									<th >Pregunta</th>
									<th >Estado</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cabeceraPreguntas as $cabeceraPregunta)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $cabeceraPregunta->id_encuesta }}</td>
										<td >{{ $cabeceraPregunta->tipo }}</td>
										<td >{{ $cabeceraPregunta->pregunta }}</td>
										<td >{{ $cabeceraPregunta->estado }}</td>

                                            <td>
                                                <form action="{{ route('cabecera-preguntas.destroy', $cabeceraPregunta->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('cabecera-preguntas.show', $cabeceraPregunta->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('cabecera-preguntas.edit', $cabeceraPregunta->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $cabeceraPreguntas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
