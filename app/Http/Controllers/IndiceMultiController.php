<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\LineasProgramaticas;
use App\Models\Encuesta;
use App\Models\VistaResultadosDimension;
use App\Models\VistaResultadosSubdimension;
use App\Models\VistaResultadosPregunta;
use Illuminate\Http\Request;

class IndiceMultiController extends Controller
{
    public function index()
    {
        $lineas = LineasProgramaticas::all();
        $encuestas = Encuesta::all();
        $valDimensiones = VistaResultadosDimension::all();
        return view('indice.index',compact('lineas','encuestas','valDimensiones'));
    }
    public function detalle($idEncuesta, $idDimension)
    {
        $valSubdimensiones = VistaResultadosSubdimension::where('id_encuesta',$idEncuesta)
            ->where('id_dimension', $idDimension)
            ->get();
        return view('indice.detalle',compact('valSubdimensiones'));
    }
}
