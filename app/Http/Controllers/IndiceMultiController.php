<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\LineasProgramaticas;
use App\Models\Encuesta;
use App\Models\EncuestaInstancia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IndiceMultiController extends Controller
{
    /**
     * DASHBOARD PRINCIPAL
     */
    public function index()
    {
        $lineas = LineasProgramaticas::all();

        $encuestas = Encuesta::select('id', 'id_linea', 'nombre')
            ->orderBy('nombre')
            ->get();

        $periodos = EncuestaInstancia::select(
                'id',
                'id_encuesta',
                'nombre_periodo',
                'fecha_desde',
                'fecha_hasta'
            )
            ->where('estado', 1)
            ->orderBy('fecha_desde', 'desc')
            ->get();

        return view('indice.index', compact(
            'lineas',
            'encuestas',
            'periodos'
        ));
    }

    /**
     * RESULTADOS POR DIMENSIÓN (RADAR + TABLA)
     */
    public function resultados(Request $request)
    {
        $request->validate([
            'encuesta_id'  => 'required|integer',
            'instancia_id' => 'required|integer',
        ]);

        $encuestaId  = $request->encuesta_id;
        $instanciaId = $request->instancia_id;

        $data = DB::table('respuestas as r')
            ->join('preguntas as p', 'p.id', '=', 'r.id_pregunta')
            ->join('subdimensiones as sd', 'sd.id', '=', 'p.id_subdimension')
            ->join('dimensiones as d', 'd.id', '=', 'sd.id_dimension')
            ->where('r.id_encuesta', $encuestaId)
            ->where('r.id_encuesta_instancia', $instanciaId)
            ->groupBy('d.id', 'd.nombre')
            ->select(
                'd.id as id_dimension',
                'd.nombre',
                DB::raw('ROUND(AVG(r.valor),2) as promedio')
            )
            ->orderBy('d.nombre')
            ->get();

        return response()->json($data);
    }

    /**
     * SEMÁFORO GLOBAL
     */
    public function semaforo(Request $request)
    {
        $request->validate([
            'encuesta_id'  => 'required|integer',
            'instancia_id' => 'required|integer',
        ]);

        $avg = DB::table('respuestas')
            ->where('id_encuesta', $request->encuesta_id)
            ->where('id_encuesta_instancia', $request->instancia_id)
            ->avg('valor');

        $avg = round($avg, 2);

        if ($avg < 5) {
            $color = 'danger';
            $estado = 'Alerta';
            $icono = 'fa-exclamation-triangle';
        } elseif ($avg < 7.5) {
            $color = 'warning';
            $estado = 'Mínimo Funcional';
            $icono = 'fa-exclamation-circle';
        } else {
            $color = 'success';
            $estado = 'Máxima Efectividad';
            $icono = 'fa-check-circle';
        }

        return response()->json([
            'promedio' => $avg,
            'color'    => $color,
            'estado'   => $estado,
            'icono'    => $icono,
        ]);
    }

    public function verDimension($encuestaId, $instanciaId, $dimensionId)
    {
        $data = DB::table('respuestas as r')
            ->join('preguntas as p', 'p.id', '=', 'r.id_pregunta')
            ->join('subdimensiones as sd', 'sd.id', '=', 'p.id_subdimension')
            ->where('r.id_encuesta', $encuestaId)
            ->where('r.id_encuesta_instancia', $instanciaId)
            ->where('p.id_subdimension', '!=', null)
            ->where('sd.id_dimension', $dimensionId)
            ->groupBy('sd.id', 'sd.nombre')
            ->select(
                'sd.nombre as nombre',
                DB::raw('ROUND(AVG(r.valor),2) as promedio')
            )
            ->get();

        return response()->json($data);
    }
}