@extends('adminlte::page')
@section('title','Importar Usuarios CSV')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">

            {{-- Encabezado con navegación de regreso --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="h4 mb-0 text-gray-800">Carga Masiva de Usuarios</h2>
                <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left"></i> Volver a Usuarios
                </a>
            </div>

            {{-- Alerta de errores de importación (Fila por fila) --}}
            @if(session('import_errors'))
                <div class="alert alert-danger shadow-sm mb-4">
                    <h5 class="alert-heading h6 fw-bold">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Se encontraron errores en el archivo CSV:
                    </h5>
                    <hr>
                    <ul class="mb-0 ps-3 small">
                        @foreach(session('import_errors') as $failure)
                            <li>
                                <strong>Fila {{ $failure->row() }}</strong> 
                                (Campo: <code>{{ $failure->attribute() }}</code>): 
                                {{ implode(', ', $failure->errors()) }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-4">
                {{-- Formulario de Carga --}}
                <div class="col-md-7">
                    <div class="card shadow-sm border-0 h-100">
                        <div class="card-header bg-white py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Subir Archivo CSV</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('usuarios.import') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                <div class="mb-4">
                                    <label for="file" class="form-label font-weight-bold">Seleccionar archivo (.csv):</label>
                                    <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror" accept=".csv" required>
                                    @error('file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Tamaño máximo del archivo: 10 MB.</small>
                                </div>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-upload me-1"></i> Procesar e Importar Usuarios
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Instrucciones y Estructura del CSV --}}
                <div class="col-md-5">
                    <div class="card shadow-sm border-0 h-100 bg-light">
                        <div class="card-body">
                            {{-- Título y texto separados correctamente --}}
                            <h6 class="card-title text-dark font-weight-bold">Estructura del Archivo CSV</h6>
                            <p class="small text-muted mt-4">El archivo debe contener exactamente las siguientes cabeceras en la primera fila:</p>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered bg-white small mb-0">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Columna</th>
                                            <th>Requerido</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td><code>email</code></td><td><span class="badge bg-danger">Sí</span></td></tr>
                                        <tr><td><code>password</code></td><td><span class="badge bg-danger">Sí</span></td></tr>
                                        <tr><td><code>nombre</code></td><td><span class="badge bg-danger">Sí</span></td></tr>
                                        <tr><td><code>ap_paterno</code></td><td><span class="badge bg-danger">Sí</span></td></tr>
                                        <tr><td><code>ap_materno</code></td><td><span class="badge bg-secondary">Opcional</span></td></tr>
                                        <tr><td><code>telefono</code></td><td><span class="badge bg-secondary">Opcional</span></td></tr>
                                        <tr><td><code>estado</code></td><td><span class="badge bg-danger">Sí (1 o 0)</span></td></tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-info py-2 px-3 mt-3 small mb-0">
                                <i class="bi bi-info-circle me-1"></i>
                                Las contraseñas se encriptarán automáticamente mediante Bcrypt al guardarse.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection