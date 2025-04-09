@extends('adminlte::page')

@section('template_title')
    Preguntas
@endsection

@section('content')
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-primary mt-3">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span id="card_title">{{ __('Preguntas') }}</span>
                            <div class="float-right">
                                <a href="{{ route('pregunta.create') }}" class="btn btn-success btn-sm float-right"  data-placement="left">Crear nuevo</a>
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
                                <thead class="thead bg-info">
                                    <tr>
                                        <th>N°</th>                                            
                                        <th>Encuesta</th>
                                        <th>Subdimension</th>
                                        <th>Texto</th>
                                        <th width="140">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($preguntas as $pregunta)
                                        <tr>
                                            <td>{{ $pregunta->id }}</td>
                                            <td>{{ $pregunta->id_encuesta }}</td>
                                            <td>{{ $pregunta->id_subdimension }}</td>
                                            <td>{{ $pregunta->texto }}</td>
                                            <td>
                                                <form action="{{ route('pregunta.disabled', $pregunta->id) }}" method="POST">
                                                    <a class="btn btn-sm btn-primary " href="{{ route('pregunta.show', $pregunta->id) }}"><i class="fa fa-fw fa-eye"></i></a>
                                                    <a class="btn btn-sm btn-success" href="{{ route('pregunta.edit', $pregunta->id) }}"><i class="fa fa-fw fa-edit"></i></a>
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="event.preventDefault(); confirm('Are you sure to delete?') ? this.closest('form').submit() : false;"><i class="fa fa-fw fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {!! $preguntas->withQueryString()->links() !!}
            </div>
        </div>
    </div>
@endsection
