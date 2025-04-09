@extends('adminlte::page')

@section('template_title')
    Mensajes
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Mensajes') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('mensajes.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Id Usuario Origen</th>
									<th >Id Usuario Destino</th>
									<th >Id Estado</th>
									<th >Asunto</th>
									<th >Mensaje</th>
									<th >Leido</th>
									<th >Readed At</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mensajes as $mensaje)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $mensaje->id_usuario_origen }}</td>
										<td >{{ $mensaje->id_usuario_destino }}</td>
										<td >{{ $mensaje->id_estado }}</td>
										<td >{{ $mensaje->asunto }}</td>
										<td >{{ $mensaje->mensaje }}</td>
										<td >{{ $mensaje->leido }}</td>
										<td >{{ $mensaje->readed_at }}</td>

                                            <td>
                                                <form action="{{ route('mensajes.destroy', $mensaje->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('mensajes.show', $mensaje->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('mensajes.edit', $mensaje->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $mensajes->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
