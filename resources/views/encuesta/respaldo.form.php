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
            <input type="text" name="descripcion" class="form-control @error('descripcion') is-invalid @enderror"
                value="{{ old('descripcion', $encuesta?->descripcion) }}" id="descripcion" placeholder="Descripcion">
            {!! $errors->first('descripcion', '<div class="invalid-feedback" role="alert"><strong>:message</strong></div>') !!}
        </div>
        <div class="form-group mb-2 mb20">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal1">Agregar
                Cabecera</button>
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#Modal2">Agregar
                Preguntas</button>
        </div>
    </div>
    <div class="col-md-12 mt20 mt-2">
        <button type="submit" name="action" value="actualizar_pregunta" class="btn btn-primary">Guardar</button>
    </div>
</div>

<!-- Contenido Collapsable -->
<div class="accordion mt-3" id="accordion">
    <div class="card card-info card-default mt-3">
        <div class="card-header" id="preguntasIngresadas">
            <h2 class="mb-0">
                <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#accordionPreguntas" aria-expanded="true" aria-controls="accordionPreguntas">Preguntas Ingresadas</button>
            </h2>
        </div>
        <div id="accordionPreguntas" class="collapse show" aria-labelledby="preguntasIngresadas" data-parent="#accordion">
            <div class="card-body">

                <!-- -->
                <div class="row">
                    <div class="col-12">
                        <div class="accordion p-3" id="accordionDimension">
                            @php
                                $countD = 0;
                            @endphp

                            @foreach($dimensiones as $dimension)
                                @if ($countD == 0)
                                <div class="card mb-0">
                                    <div class="card-header card-primera" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-white" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                {{ $dimension->nombre }}
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionDimension">
                                        <div class="card-body">
                                            @if (isset($preguntas))
                                                <table class="table mt-3">
                                                    <thead class="table-info">
                                                        <tr>
                                                            <th scope="col">ID</th>
                                                            <th scope="col">Subdimension</th>
                                                            <th scope="col">Pregunta</th>
                                                            <th scope="col" width="125">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="">
                                                        @foreach ($preguntas as $pregunta)
                                                            <tr>
                                                                <th scope="row">{{ $pregunta->id }}</th>
                                                                <td>{{ $pregunta->nombre }}</td>
                                                                <td>{{ $pregunta->texto }}</td>
                                                                <td>
                                                                    <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#ModalA{{ $pregunta->id }}" title="Agregar Alternativa"><i class="fas fa-plus-square"></i></button>
                                                                    <button type="button" class="btn btn-sm btn btn-warning" data-toggle="modal" data-target="#ModalB{{ $pregunta->id }}" title="Editar Pregunta"><i class="fas fa-edit"></i></button>
                                                                    <button class="btn btn-sm btn-danger" title="Eliminar Pregunta"><i class="fas fa-trash-alt"></i></button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="4">
                                                                    <table class="table table-striped table-sm">
                                                                        <thead class="bg-info">
                                                                            <th class="mx-auto">ID</th>
                                                                            <th class="mx-auto">Valor</th>
                                                                            <th>Pregunta</th>
                                                                        </thead>
                                                                        <tbody class="bg-light">
                                                                        @foreach ($alternativas as $alter)
                                                                            @if ($alter->id_pregunta == $pregunta->id)
                                                                                <tr>
                                                                                    <th class="mx-auto">{{ $alter->id }}</th>
                                                                                    <td class="mx-auto">{{ $alter->valor }}</td>
                                                                                    <td>{{ $alter->texto }}</td>
                                                                                </tr>
                                                                            @endif
                                                                        @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            @include('encuesta.modal3')
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            @else
                                                <span>Aun no se han ingresado Preguntas</span>
                                            @endif
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
                                                {{ $dimension->descripcion}}
                                            </div>
                                        </div>
                                    </div>     
                                @endif
                                @php
                                    $countD++;
                                @endphp
                            @endforeach

                            
                            <!--
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Collapsible Group Item #2
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionDimension">
                                    <div class="card-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Collapsible Group Item #3
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionDimension">
                                    <div class="card-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                </div>
                            </div>
                            -->
                        </div>
                    </div>
                </div>

        <!--
        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
            <div class="card-body">
                @if (isset($preguntas))
                    <table class="table mt-3">
                        <thead class="table-info">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Subdimension</th>
                                <th scope="col">Pregunta</th>
                                <th scope="col" width="125">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="">
                            @foreach ($preguntas as $pregunta)
                                <tr>
                                    <th scope="row">{{ $pregunta->id }}</th>
                                    <td>{{ $pregunta->nombre }}</td>
                                    <td>{{ $pregunta->texto }}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal" data-target="#ModalA{{ $pregunta->id }}" title="Agregar Alternativa"><i class="fas fa-plus-square"></i></button>
                                        <button type="button" class="btn btn-sm btn btn-warning" data-toggle="modal" data-target="#ModalB{{ $pregunta->id }}" title="Editar Pregunta"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger" title="Eliminar Pregunta"><i class="fas fa-trash-alt"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <table class="table table-striped table-sm">
                                            <thead class="bg-info">
                                                <th class="mx-auto">ID</th>
                                                <th class="mx-auto">Valor</th>
                                                <th>Pregunta</th>
                                            </thead>
                                            <tbody class="bg-light">
                                            @foreach ($alternativas as $alter)
                                                @if ($alter->id_pregunta == $pregunta->id)
                                                    <tr>
                                                        <th class="mx-auto">{{ $alter->id }}</th>
                                                        <td class="mx-auto">{{ $alter->valor }}</td>
                                                        <td>{{ $alter->texto }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                @include('encuesta.modal3')
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <span>Aun no se han ingresado Preguntas</span>
                @endif
            </div>
        </div>
        -->


    <div class="card card-success card-default mt-3">
        <div class="card-header" id="headingTwo">
            <h2 class="mb-0">
                <button class="btn btn-link collapsed text-white" type="button" data-toggle="collapse"
                    data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Preguntas de Cabecera
                    (Generales sin Evaluación)</button>
            </h2>
        </div>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
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
                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                            data-target="#ModalA{{ $cabeza->id }}" title="Agregar Alternativa"><i
                                                class="fas fa-plus-square"></i></button>
                                        <button class="btn btn-sm btn-warning" title="Editar Pregunta"><i
                                                class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-danger" title="Eliminar Pregunta"><i
                                                class="fas fa-trash-alt"></i></button>
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
