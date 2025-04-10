<input type="hidden" name="estado" value="1">
<div class="row padding-1 p-1">
    <div class="col-md-12">
        <div class="form-group mb-2 mb20">
            <label for="nombre" class="form-label">{{ __('Nombre') }}</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                value="{{ old('nombre', $encuesta?->nombre) }}" id="nombre" placeholder="Nombre">
            {!! $errors->first('nombre', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="descripcion" class="form-label">{{ __('Descripcion') }}</label>
            <!--
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                value="{{ old('descripcion', $encuesta?->descripcion) }}" id="descripcion" placeholder="Descripcion">
            -->
            <textarea name="descripcion"  class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" placeholder="Descripcion">
                {{ old('descripcion', $encuesta?->descripcion) }}
            </textarea>
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <label for="linea" class="form-label">Linea Programatica</label>
            <select name="linea" id="linea" class="form-control">
                @if($encuesta->id_linea==0)
                    <option value="0" selected>Seleccione una Linea programatica</option>
                @endif
                @foreach ($lineas as $linea)
                    @if($encuesta->id_linea == $linea->id)
                    <option value="{{ $linea->id }}" selected>{{ $linea->nombre }}</option>
                    @else
                    <option value="{{ $linea->id }}">{{ $linea->nombre }}</option>
                    @endif
                @endforeach
                
            </select>
        </div>
        <div class="form-group mb-2 mb20">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal1">Agregar Cabecera</button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal2">Agregar Preguntas</button>
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" name="action" value="actualizar_pregunta" class="btn btn-primary">Guardar</button>
    </div>
</div>
<!-- Contenido Collapsable -->
<div class="accordion mt-3" id="accordion">
    <!-- Acordeón Preguntas Ingresadas -->
    <div class="card card-info card-default mt-3">
        <div class="card-header" id="preguntasIngresadas">
            <h2 class="mb-0">
                <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#accordionPreguntas" aria-expanded="true" aria-controls="accordionPreguntas">Preguntas Ingresadas</button>
            </h2>
        </div>
        <div id="accordionPreguntas" class="collapse show" aria-labelledby="preguntasIngresadas" data-parent="#accordion">
            <div class="card-body">
                <!-- Acordeon Dimensiones -->
                <div class="row">
                    <div class="col-12">
                        <div class="accordion p-3" id="accordionDimension">
                            @php
                                $countD = 0;
                                $countPreg = 1;
                            @endphp
                            @if(isset($dimensiones))
                                @foreach($dimensiones as $dimension)
                                    @if ($countD == 0)
                                        <div class="card mb-0">
                                            <div class="card-header card-primera" id="headingD">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#collapseD" aria-expanded="true" aria-controls="collapseD">
                                                        {{ $dimension->nombre }}
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseD" class="collapse show" aria-labelledby="headingD" data-parent="#accordionDimension">
                                                <div class="card-body">
                                                    <span>{!! $dimension->descripcion !!}</span>
                                                    <div id="accordionSD" data-collapse-type="manual" >
                                                        @php
                                                            $countSD = 0;
                                                        @endphp
                                                        @foreach($subdimensiones as $subd)
                                                            @php
                                                                if ($countSD==0){
                                                                    $ariaExpanded = "true";
                                                                    $classExpanded = "btn btn-link";
                                                                    $classShow = "collapse show";
                                                                }   
                                                                else {
                                                                    $ariaExpanded = "false";
                                                                    $classExpanded = "btn btn-link collapsed";
                                                                    $classShow = "collapse";
                                                                }
                                                            @endphp
                                                            @if($subd->id_dimension == $dimension->id)                                                
                                                                <div class="card mb-0">
                                                                    <div class="card-header card-segunda" id="head{{ $subd->id }}SD">
                                                                        <a type="buttom" class="{{ $classExpanded }} text-white" data-toggle="collapse" data-target="#collapse{{ $subd->id}}SD" aria-expanded="{{ $ariaExpanded }}" aria-controls="collapse{{ $subd->id}}SD">
                                                                            {{ $subd->nombre }}
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse{{ $subd->id}}SD" class="{{ $classShow }}" aria-labelledby="head{{ $subd->id}}SD" data-parent="#accordionSD">
                                                                        <div class="card-body">
                                                                            <!-- -->
                                                                            @if (isset($preguntas)) 
                                                                                <table class="table mt-3">
                                                                                    <thead class="table-info">
                                                                                        <tr>
                                                                                            <th scope="col">Codigo</th>
                                                                                            <th scope="col">Subdimension</th>
                                                                                            <th scope="col">Pregunta</th>
                                                                                            <th scope="col" width="125">Acciones</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="">
                                                                                        @foreach ($preguntas as $pregunta)
                                                                                            @if($pregunta->id_subdimension == $subd->id)
                                                                                                <tr>
                                                                                                    <th>{{ $dimension->id}}.{{ $subd->id}}.{{ $pregunta->posicion }}</td>
                                                                                                    <td>{{ $pregunta->nombre }}</td>
                                                                                                    <td>{!! $pregunta->texto !!}</td>
                                                                                                    <td>
                                                                                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#ModalA{{ $pregunta->id }}" title="Agregar Alternativa"><i class="fas fa-plus-square"></i></button>
                                                                                                        <a type="button" class="btn btn-sm btn btn-warning" href="{{ route('pregunta.edit',$pregunta->id)}}" title="Editar Pregunta"><i class="fas fa-edit"></i></a>
                                                                                                        <a type="buttom" class="btn btn-danger btn-sm" href="{{ route('pregunta.disabled', $pregunta->id) }}"><i class="fas fa-trash-alt"></i></button>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="4">
                                                                                                        <table class="table table-striped table-sm">
                                                                                                            <thead class="bg-info">
                                                                                                                <th class="mx-auto">Valor</th>
                                                                                                                <th>Pregunta</th>
                                                                                                                <th width="80">Acciones</th>
                                                                                                            </thead>
                                                                                                            <tbody class="bg-light">
                                                                                                            @foreach ($alternativas as $alter)
                                                                                                                @if ($alter->id_pregunta == $pregunta->id)
                                                                                                                    <tr>
                                                                                                                        <td class="mx-auto">{{ $alter->valor }}</td>
                                                                                                                        <td>{!! $alter->texto !!}</td>
                                                                                                                        <td>
                                                                                                                            <a type="button" class="btn btn-sm btn-warning" href="{{ route('alternativa.edit',$alter->id) }}" title="Editar Alternativa"><i class="fas fa-edit"></i></a>
                                                                                                                            <a type="buttom" class="btn btn-sm btn-danger" href="{{ route('alternativa.disabled',$alter->id) }}"><i class="fas fa-trash-alt"></i></button>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                @include('encuesta.modal3')
                                                                                            @endif
                                                                                            @php
                                                                                                $countPreg++;
                                                                                            @endphp
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            @else
                                                                                <span>Aun no se han ingresado Preguntas</span>
                                                                            @endif
                                                                            <!-- -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @php
                                                                $countSD++;
                                                            @endphp
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="card mb-0">
                                            <div class="card-header card-primera" id="headingTwo{{$countD}}">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link collapsed text-white" type="button" data-toggle="collapse" data-target="#collapseTwo{{$countD}}" aria-expanded="false" aria-controls="collapseTwo{{$countD}}">
                                                        {{ $dimension->nombre }}
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="collapseTwo{{$countD}}" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionDimension">
                                                <div class="card-body">
                                                    <!-- -->
                                                    <div id="accordionSDs" data-collapse-type="manual" >
                                                        @php
                                                            $countSD = 0;
                                                        @endphp
                                                        @foreach($subdimensiones as $subd)
                                                            @php
                                                                if ($countSD==0){
                                                                    $ariaExpanded = "true";
                                                                    $classExpanded = "btn btn-link";
                                                                    $classShow = "collapse show";
                                                                }   
                                                                else {
                                                                    $ariaExpanded = "false";
                                                                    $classExpanded = "btn btn-link collapsed";
                                                                    $classShow = "collapse";
                                                                }
                                                            @endphp
                                                            @if($subd->id_dimension == $dimension->id)                                                
                                                                <div class="card mb-0">
                                                                    <div class="card-header card-segunda" id="head{{ $subd->id }}SDs">
                                                                        <a type="buttom" class="{{ $classExpanded }} text-white" data-toggle="collapse" data-target="#collapse{{ $subd->id}}SDs" aria-expanded="{{ $ariaExpanded }}" aria-controls="collapse{{ $subd->id}}SDs">
                                                                            {{ $subd->nombre }}
                                                                        </a>
                                                                    </div>
                                                                    <div id="collapse{{ $subd->id}}SDs" class="{{ $classShow }}" aria-labelledby="head{{ $subd->id}}SDs" data-parent="#accordionSDs">
                                                                        <div class="card-body">
                                                                            <!-- -->
                                                                            @if (isset($preguntas)) 
                                                                                <table class="table mt-3">
                                                                                    <thead class="table-info">
                                                                                        <tr>
                                                                                            <th scope="col">Codigo</th>
                                                                                            <th scope="col">Subdimension</th>
                                                                                            <th scope="col">Pregunta</th>
                                                                                            <th scope="col" width="125">Acciones</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody class="">
                                                                                        @foreach ($preguntas as $pregunta)
                                                                                            @if($pregunta->id_subdimension == $subd->id)
                                                                                                <tr>
                                                                                                    <th>{{ $dimension->id}}.{{ $subd->id}}.{{ $pregunta->posicion }}</td>
                                                                                                    <td>{{ $pregunta->nombre }}</td>
                                                                                                    <td>{!! $pregunta->texto !!}</td>
                                                                                                    <td>
                                                                                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#ModalA{{ $pregunta->id }}" title="Agregar Alternativa"><i class="fas fa-plus-square"></i></button>
                                                                                                        <a type="button" class="btn btn-sm btn btn-warning" href="{{ route('pregunta.edit',$pregunta->id)}}" title="Editar Pregunta"><i class="fas fa-edit"></i></a>
                                                                                                        <a type="buttom" class="btn btn-danger btn-sm" href="{{ route('pregunta.disabled', $pregunta->id) }}"><i class="fas fa-trash-alt"></i></button>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                <tr>
                                                                                                    <td colspan="4">
                                                                                                        <table class="table table-striped table-sm">
                                                                                                            <thead class="bg-info">
                                                                                                                <th class="mx-auto">Valor</th>
                                                                                                                <th>Pregunta</th>
                                                                                                                <th width="80">Acciones</th>
                                                                                                            </thead>
                                                                                                            <tbody class="bg-light">
                                                                                                            @foreach ($alternativas as $alter)
                                                                                                                @if ($alter->id_pregunta == $pregunta->id)
                                                                                                                    <tr>
                                                                                                                        <td class="mx-auto">{{ $alter->valor }}</td>
                                                                                                                        <td>{!! $alter->texto !!}</td>
                                                                                                                        <td>
                                                                                                                            <a type="button" class="btn btn-sm btn-warning" href="{{ route('alternativa.edit',$alter->id) }}" title="Editar Alternativa"><i class="fas fa-edit"></i></a>
                                                                                                                            <a type="buttom" class="btn btn-sm btn-danger" href="{{ route('alternativa.disabled',$alter->id) }}"><i class="fas fa-trash-alt"></i></button>
                                                                                                                        </td>
                                                                                                                    </tr>
                                                                                                                @endif
                                                                                                            @endforeach
                                                                                                            </tbody>
                                                                                                        </table>
                                                                                                    </td>
                                                                                                </tr>
                                                                                                @include('encuesta.modal3')
                                                                                            @endif
                                                                                        @endforeach
                                                                                    </tbody>
                                                                                </table>
                                                                            @else
                                                                                <span>Aun no se han ingresado Preguntas</span>
                                                                            @endif
                                                                            <!-- -->
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                            @php
                                                                $countSD++;
                                                            @endphp
                                                        @endforeach
                                                    </div>
                                                    <!-- -->
                                                </div>
                                            </div>
                                        </div>     
                                    @endif
                                    @php
                                        $countD++;
                                    @endphp
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- Acordeón Preguntas Cabecera -->
    <div class="card card-success card-default">
        <div class="card-header" id="preguntasCabecera">
            <h2 class="mb-0">
                <button class="btn btn-link collapsed text-white" type="button" data-toggle="collapse"
                    data-target="#accordionCabecera" aria-expanded="false" aria-controls="accordionCabecera">Preguntas de Cabecera
                    (Generales sin Evaluación)</button>
            </h2>
        </div>
        <div id="accordionCabecera" class="collapse" aria-labelledby="preguntasCabecera" data-parent="#accordion">
            <div class="card-body">
                @if (isset($cabeceras))
                    <table class="table table-ligth mt-3">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">ID Encuesta</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Pregunta</th>
                                <th scope="col">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cabeceras as $cabeza)
                                <tr>
                                    <th scope="row">{{ $cabeza->id }}</th>
                                    <td>{{ $cabeza->id_encuesta }}</td>
                                    <td>{{ $cabeza->tipo }}</td>
                                    <td>{{ $cabeza->pregunta }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#ModalA{{ $cabeza->id }}" title="Agregar Alternativa"><i class="fas fa-plus-square"></i></button>
                                        <button class="btn btn-sm btn-warning" title="Editar Pregunta"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger" title="Eliminar Pregunta"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <ul>
                                            @foreach ($cabeceras_alternativas as $cabalter)
                                                @if ($cabalter->id_cabecera == $cabeza->id)
                                                    <li>{{ $cabalter->pregunta }}</li>
                                                @endif
                                            @endforeach
                                            <ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <span>No hay preguntas de cabecera ingresadas aún</span>
                @endif
            </div>
        </div>
    </div>
</div>

@include('encuesta.modal1')
@include('encuesta.modal2')

<script>
    function activa_dependencia(){
        var tipo = document.getElementById("tipo");
        var y = tipo.value;
        if (y == 1){
            document.getElementById("depende").style.display = 'block';
        } else {
            document.getElementById("depende").style.display = 'none';
        }
    }
    function activa_pregunta() {
        var ctipo = document.getElementById("ctipo");
        var x = ctipo.value;
        if (x == 1) {
            document.getElementById("dalter").style.display = 'block';
        } else {
            document.getElementById("dalter").style.display = 'none';
        }
    }
    const input_nombre = document.querySelector("#input_nombre");
    const btn_agregar = document.querySelector("#btn_agregar");
    const ul_lista = document.querySelector("#ul_lista");

    const agregarItem = (e) => {
        e.preventDefault();
        if (input_nombre.value != "") {
            let nombre = input_nombre.value;
            let li = document.createElement("li");
            li.innerHTML =
                `<span>${nombre}</span> <input type="hidden" name="calter[]" value="${nombre}" /> <a href="" class="btn btn-xs btn-danger btn_eliminar ml-3"> <i class="fas fa-trash-alt"></i> </a>`;
            ul_lista.appendChild(li);
            li.querySelector(".btn_eliminar").addEventListener("click", (e) => {
                e.preventDefault();
                ul_lista.removeChild(li);
            });
        }
    }
    btn_agregar.addEventListener("click", agregarItem);
</script>
