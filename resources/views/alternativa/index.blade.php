@extends('adminlte::page')

@section('template_title')
    Alternativas
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">

                            <span id="card_title">
                                {{ __('Alternativas') }}
                            </span>

                             <div class="float-right">
                                <a href="{{ route('alternativa.create') }}" class="btn btn-primary btn-sm float-right"  data-placement="left">
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
                                        
									<th >Id Pregunta</th>
									<th >Texto</th>
									<th >Valor</th>

                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($alternativas as $alternativa)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            
										<td >{{ $alternativa->id_pregunta }}</td>
										<td >{{ $alternativa->texto }}</td>
										<td >{{ $alternativa->valor }}</td>

                                            <td>
                                                <form action="{{ route('alternativa.disabled', $alternativa->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('alternativa.show', $alternativa->id) }}"><i class="fa fa-fw fa-eye"></i> {{ __('Show') }}</a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('alternativa.edit', $alternativa->id) }}"><i class="fa fa-fw fa-edit"></i> {{ __('Edit') }}</a>
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
                {!! $alternativas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
