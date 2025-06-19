@extends('adminlte::page')
@section('title','Asignar Permisos')
@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-default mt-3">
                    <div class="card-header">
                        <span class="card-title">Asignar Permisos</span>
                    </div>
                    <div class="card-body bg-white">
                        <form method="POST" action="{{ route('role.asignar', $rol->id) }}" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="form-group">
                                <label for="permisos">Seleccionar Permisos</label>
                                <select name="permisos[]" id="permisos" class="form-control" multiple>
                                    @php
                                        $permisosAsignados = $rol->permisos->pluck('id')->toArray();
                                    @endphp

                                    @foreach ($permisos as $permiso)
                                        <option value="{{ $permiso->id }}" 
                                            {{ in_array($permiso->id, $permisosAsignados) ? 'selected' : '' }}>
                                            {{ $permiso->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success">Asignar Permisos</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection