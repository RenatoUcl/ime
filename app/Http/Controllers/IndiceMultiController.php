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

        // IMPORTANTE:
        // Traemos TODOS los periodos (activos e inactivos)
        $periodos = EncuestaInstancia::select(
                'id',
                'id_encuesta',
                'nombre_periodo',
                'fecha_desde',
                'fecha_hasta',
                'estado'
            )
            ->orderBy('fecha_desde', 'desc')
            ->get();

        return view('indice.index', compact(
            'lineas',
            'encuestas',
            'periodos'
        ));
    }

    /**
     * RESULTADOS POR DIMENSIÓN
     *
     * MODELO CORRECTO:
     * 1) Subdimensión = SUM(valor) / usuarios
     * 2) Dimensión = (subdim1 + subdim2 + subdim3) / 3
     */
    public function resultados(Request $request)
    {
        $request->validate([
            'encuesta_id'  => 'required|integer',
            'instancia_id' => 'required|integer',
        ]);

        $encuestaId  = $request->encuesta_id;
        $instanciaId = $request->instancia_id;

        /**
         * 1️⃣ Cantidad de usuarios que respondieron esta encuesta en este período
         */
        $usuarios = DB::table('respuestas')
            ->where('id_encuesta', $encuestaId)
            ->where('id_encuesta_instancia', $instanciaId)
            ->distinct('id_usuario')
            ->count('id_usuario');

        if ($usuarios === 0) {
            return response()->json([]);
        }

        /**
         * 2️⃣ Subdimensiones:
         *    SUM(valor) / usuarios
         */
        $subdimensiones = DB::table('respuestas as r')
            ->join('preguntas as p', 'p.id', '=', 'r.id_pregunta')
            ->join('subdimensiones as sd', 'sd.id', '=', 'p.id_subdimension')
            ->join('dimensiones as d', 'd.id', '=', 'sd.id_dimension')
            ->where('r.id_encuesta', $encuestaId)
            ->where('r.id_encuesta_instancia', $instanciaId)
            ->groupBy('sd.id', 'sd.id_dimension')
            ->select(
                'sd.id as id_subdimension',
                'sd.id_dimension',
                DB::raw('SUM(r.valor) / '.$usuarios.' as valor_subdimension')
            )
            ->get();

        /**
         * 3️⃣ Agrupar subdimensiones por dimensión
         *    Cada dimensión tiene 3 subdimensiones
         */
        $dimensiones = [];

        foreach ($subdimensiones as $sd) {
            if (!isset($dimensiones[$sd->id_dimension])) {
                $dimensiones[$sd->id_dimension] = [
                    'suma' => 0,
                    'count' => 0
                ];
            }

            $dimensiones[$sd->id_dimension]['suma']  += $sd->valor_subdimension;
            $dimensiones[$sd->id_dimension]['count'] += 1;
        }

        /**
         * 4️⃣ Normalizar dimensión: (sub1 + sub2 + sub3) / 3
         */
        $resultadoFinal = [];

        foreach ($dimensiones as $idDimension => $data) {

            // Seguridad: si por algún motivo no hay 3 subdimensiones
            if ($data['count'] === 0) {
                continue;
            }

            $dimension = Dimension::find($idDimension);

            if (!$dimension) {
                continue;
            }

            $resultadoFinal[] = [
                'id_dimension' => $dimension->id,
                'nombre'       => $dimension->nombre,
                'promedio'     => round($data['suma'] / $data['count'], 2)
            ];
        }

        // Orden consistente
        usort($resultadoFinal, function ($a, $b) {
            return strcmp($a['nombre'], $b['nombre']);
        });

        return response()->json($resultadoFinal);
    }

    /**
     * SEMÁFORO GLOBAL
     *
     * PROMEDIO DE DIMENSIONES (NO de respuestas)
     */
    public function semaforo(Request $request)
    {
        $request->validate([
            'encuesta_id'  => 'required|integer',
            'instancia_id' => 'required|integer',
        ]);

        $requestResultados = new Request([
            'encuesta_id'  => $request->encuesta_id,
            'instancia_id' => $request->instancia_id,
        ]);

        $dimensiones = $this->resultados($requestResultados)->getData(true);

        if (empty($dimensiones)) {
            return response()->json([
                'promedio' => 0,
                'color'    => 'secondary',
                'estado'   => 'Sin datos',
                'icono'    => 'fa-ban',
            ]);
        }

        $promedio = collect($dimensiones)->avg('promedio');
        $promedio = round($promedio, 2);

        if ($promedio < 5) {
            $color = 'danger';
            $estado = 'Alerta';
            $icono = 'fa-exclamation-triangle';
        } elseif ($promedio < 7.5) {
            $color = 'warning';
            $estado = 'Mínimo Funcional';
            $icono = 'fa-exclamation-circle';
        } else {
            $color = 'success';
            $estado = 'Máxima Efectividad';
            $icono = 'fa-check-circle';
        }

        return response()->json([
            'promedio' => $promedio,
            'color'    => $color,
            'estado'   => $estado,
            'icono'    => $icono,
        ]);
    }

public function verDimension($encuestaId, $dimensionId, $instanciaId)
{
    /*
     * 1. Cantidad de usuarios que respondieron
     *    (para esta encuesta y período)
     */
    $totalUsuarios = DB::table('respuestas')
        ->where('id_encuesta', $encuestaId)
        ->where('id_encuesta_instancia', $instanciaId)
        ->distinct('id_usuario')
        ->count('id_usuario');

    if ($totalUsuarios === 0) {
        return response()->json([
            'dimension' => null,
            'subdimensiones' => []
        ]);
    }

    /*
     * 2. Nombre de la dimensión
     */
    $nombreDimension = DB::table('dimensiones')
        ->where('id', $dimensionId)
        ->value('nombre');

    /*
     * 3. Subdimensiones con cálculo CORRECTO:
     *    (SUMA respuestas) / (usuarios)
     */
    $subdimensiones = DB::table('respuestas as r')
        ->join('preguntas as p', 'p.id', '=', 'r.id_pregunta')
        ->join('subdimensiones as sd', 'sd.id', '=', 'p.id_subdimension')
        ->where('r.id_encuesta', $encuestaId)
        ->where('r.id_encuesta_instancia', $instanciaId)
        ->where('sd.id_dimension', $dimensionId)
        ->groupBy('sd.id', 'sd.nombre')
        ->select(
            'sd.nombre',
            DB::raw('ROUND(SUM(r.valor) / ' . $totalUsuarios . ', 2) as valor')
        )
        ->orderBy('sd.nombre')
        ->get();

    return response()->json([
        'dimension' => $nombreDimension,
        'subdimensiones' => $subdimensiones
    ]);
}

    /**
     * HISTÓRICO POR DIMENSIÓN
     * (opcional, queda consistente con el modelo)
     */
    public function historicoDimension($encuesta, $dimension)
    {
        return DB::table('respuestas as r')
            ->join('preguntas as p', 'p.id', '=', 'r.id_pregunta')
            ->join('subdimensiones as sd', 'sd.id', '=', 'p.id_subdimension')
            ->join('encuestas_instancias as ei', 'ei.id', '=', 'r.id_encuesta_instancia')
            ->where('r.id_encuesta', $encuesta)
            ->where('sd.id_dimension', $dimension)
            ->groupBy('ei.id', 'ei.nombre_periodo')
            ->select(
                'ei.nombre_periodo as periodo',
                DB::raw('ROUND(AVG(r.valor),2) as promedio')
            )
            ->orderBy('ei.fecha_desde')
            ->get();
    }
}