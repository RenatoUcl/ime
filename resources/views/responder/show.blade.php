@extends('adminlte::page')

@section('template_title')
    {{ $encuesta->name ?? __('Show') . ' ' . __('Encuesta') }}
@endsection

@section('content')
    <section class="content container-fluid">
        <div class="row">
            <div class="col-md-12 mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span class="card-title">Responder Encuesta</span>
                            <div class="float-right">
                                <a class="btn btn-info btn-sm" href="{{ route('responder.index') }}"><i class="fas fa-arrow-left"></i>&nbsp;&nbsp;&nbsp; Volver</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body bg-white">
                        <div class="form-group mb-2 mb20">
                            <strong>Nombre:</strong>
                            {{ $encuesta->nombre }}
                        </div>
                        <div class="form-group mb-2 mb20">
                            <strong>Descripcion:</strong>
                            {{ $encuesta->descripcion }}
                        </div>
                        <!-- -->
                        {{ dd($alternativas->toArray()) }}
                        <!-- -->
                        <form action="{{ route('responder.save')}}" method="POST" role="form" enctype="multipart/form-data">
                            @csrf
                            @method('POST')
                            <div class="accordion" id="accordion">
                                <div class="card card-info">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn text-white font-weight-bold" type="button"
                                                data-toggle="collapse" data-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                Preguntas Generales
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                        <div class="card-body">
                                            @foreach ($cabeceras as $cab)
                                                <div class="card border-secondary card-info mb-3">
                                                    <div class="card-header font-weight-bold">{{ $cab->pregunta }}</div>
                                                    <div class="card-body text-secondary">
                                                        @if ($cab->tipo == "2")
                                                            <div class="form-group">
                                                                <label for="cabresp{{$cab->id}}">Ingrese Respuesta</label>
                                                                <textarea name="cabresp{{$cab->id}}" id="cabresp{{$cab->id}}" cols="30" rows="10" placeholder="Ingrese su Respuesta" class="form-control"></textarea>
                                                            </div>
                                                        @else
                                                            @foreach ($cabalternativas as $calter)
                                                                @if ($calter->id_cabecera == $cab->id)
                                                                    <input type="radio" name="cabresp{{ $cab->id }}" id="cabresp{{ $cab->id }}" value="{{ $calter->id }}">
                                                                    <label for="cabresp{{ $cab->id }}">{{ $calter->pregunta }}</label>
                                                                    </br>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="card-footer">Pregunta: {{ $cab->id }} || Encuesta : {{ $cab->id_encuesta }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-info">
                                    <div class="card-header" id="headingTwo">
                                        <h2 class="mb-0">
                                            <button class="btn collapsed text-white font-weight-bold" type="button"
                                                data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false"
                                                aria-controls="collapseTwo">
                                                Preguntas Calculadas
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo"
                                        data-parent="#accordion">
                                        <div class="card-body">
                                            <!--
                                            @foreach ($preguntas as $preg)
                                                <div class="card border-secondary card-info mb-3">
                                                    <div class="card-header font-weight-bold">{{ $preg->texto }}</div>
                                                    <div class="card-body text-secondary">
                                                        @foreach ($alternativas as $alter)
                                                            <div class="form-group">
                                                                @if ($alter->id_pregunta == $preg->id)
                                                                    <input type="radio" name="respuesta{{ $preg->id }}" id="respuesta{{ $preg->id }}" value="{{ $alter->id }}|{{ $alter->valor}}" class="align-middle">

                                                                    <label for="respuesta{{ $preg->id }}" class="align-middle">{{ $alter->texto }}</label>
                                                                    </br>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="card-footer">Pregunta: {{ $preg->id }} || Encuesta : {{ $preg->id_encuesta }} || Subdimension : {{ $preg->nombre }}</div>
                                                </div>
                                            @endforeach
                                            -->

                                            <!-- Separación x Dimension -->
                                                            <!-- Acordeon Dimensiones -->
                <div class="row">
                    <div class="col-12">
                        <div class="accordion p-3" id="accordionDimension">
                            @php
                                $countD = 0;
                            @endphp

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
                                                                            @foreach ($preguntas as $preg)
                                                                                @if($preg->id_subdimension == $subd->id)
                                                                                <div class="card border-secondary card-info mb-3">
                                                                                    <div class="card-header font-weight-bold">{{ $preg->texto }}</div>
                                                                                    <div class="card-body text-secondary">
                                                                                        @foreach ($alternativas as $alter)
                                                                                            <div class="form-group">
                                                                                                @if ($alter->id_pregunta == $preg->id)
                                                                                                    <input type="radio" name="respuesta{{ $preg->id }}" id="respuesta{{ $preg->id }}" value="{{ $alter->id }}|{{ $alter->valor}}" class="align-middle">
                                
                                                                                                    <label for="respuesta{{ $preg->id }}" class="align-middle">{{ $alter->texto }}</label>
                                                                                                    </br>
                                                                                                @endif
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                    <div class="card-footer">Pregunta: {{ $preg->id }} || Encuesta : {{ $preg->id_encuesta }} || Subdimension : {{ $preg->nombre }}</div>
                                                                                </div>
                                                                                @endif
                                                                            @endforeach
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
                                                                        @if (isset($preguntas)) 
                                                                            @foreach ($preguntas as $preg)
                                                                                @if($preg->id_subdimension == $subd->id)
                                                                                <div class="card border-secondary card-info mb-3">
                                                                                    <div class="card-header font-weight-bold">{{ $preg->texto }}</div>
                                                                                    <div class="card-body text-secondary">
                                                                                        @foreach ($alternativas as $alter)
                                                                                            <div class="form-group">
                                                                                                @if ($alter->id_pregunta == $preg->id)
                                                                                                    <input type="radio" name="respuesta{{ $preg->id }}" id="respuesta{{ $preg->id }}" value="{{ $alter->id }}|{{ $alter->valor}}" class="align-middle">
                                
                                                                                                    <label for="respuesta{{ $preg->id }}" class="align-middle">{{ $alter->texto }}</label>
                                                                                                    </br>
                                                                                                @endif
                                                                                            </div>
                                                                                        @endforeach
                                                                                    </div>
                                                                                    <div class="card-footer">Pregunta: {{ $preg->id }} || Encuesta : {{ $preg->id_encuesta }} || Subdimension : {{ $preg->nombre }}</div>
                                                                                </div>
                                                                                @endif
                                                                            @endforeach
                                                                        @else
                                                                            <span>Aun no se han ingresado Preguntas</span>
                                                                        @endif
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
                        </div>
                    </div>
                </div>
                                            <!-- -->

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <input type="hidden" id="id_encuesta" name="id_encuesta" value="{{ $encuesta->id }}" />
                                <button type="reset" class="btn btn-warning"> <i class="fas fa-eraser"></i> Limpiar </button>
                                <button type="submit" class="btn btn-success"> <i class="far fa-save"></i> Guardar </button>
                            </div>
                        </form>
                        <!-- -->
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
