<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Dimension;
use App\Models\Subdimension;
use App\Models\EncuestasUsuario;
use App\Models\EncuestaUsuarioDimension;
use App\Models\Respuesta;
use App\Models\Alternativa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EncuestaFlujoController extends Controller
{
    public function start($encuestaId)
    {
        $userId = Auth::id();
        $encuesta = Encuesta::findOrFail($encuestaId);

        // 1) Período activo (HOY)
        $hoy = now()->toDateString();

        $instancia = DB::table('encuestas_instancias')
            ->where('id_encuesta', $encuestaId)
            ->whereDate('fecha_desde', '<=', $hoy)
            ->whereDate('fecha_hasta', '>=', $hoy)
            ->where('estado', 1)
            ->orderByDesc('id')
            ->first();

        if (!$instancia) {
            abort(403, 'Esta encuesta no tiene un Período de aplicación activo hoy.');
        }

        $instanciaId = (int) $instancia->id;

        // 2) Dimensiones permitidas para este usuario
        $dimensionesPermitidas = EncuestaUsuarioDimension::where('id_usuario', $userId)
            ->where('id_encuesta', $encuestaId)
            ->orderBy('orden')
            ->pluck('id_dimension')
            ->toArray();

        if (empty($dimensionesPermitidas)) {
            abort(403, 'No tiene permiso para responder esta encuesta.');
        }

        // 3) Reanudación / sesión por (encuesta, usuario, período)
        $sesion = EncuestasUsuario::firstOrCreate(
            [
                'id_encuesta' => $encuestaId,
                'id_encuesta_instancia' => $instanciaId,
                'id_usuario'  => $userId,
            ],
            [
                'ultima_pregunta_id' => null,
                'ultimo_grupo'       => 1,
                'completado'         => 0,
            ]
        );

        // 4) Bloqueo: 1 vez por período
        if ((int)$sesion->completado === 1) {
            abort(403, 'Usted ya respondió esta encuesta en el Período de aplicación actual.');
        }

        // Total pantallas (dimensiones * 3 subdimensiones)
        $totalPantallas = count($dimensionesPermitidas) * 3;

        session([
            "flujo_{$encuestaId}_dimensiones" => $dimensionesPermitidas,
            "flujo_{$encuestaId}_total"       => $totalPantallas,
            "flujo_{$encuestaId}_sesion"      => $sesion->id,
            "flujo_{$encuestaId}_instancia"   => $instanciaId,
        ]);

        return redirect()->route('encuestas.flujo.grupo', [
            'encuestaId' => $encuestaId,
            'grupo'      => $sesion->ultimo_grupo,
        ]);
    }

    public function showGrupo($encuestaId, $grupo)
    {
        $userId = Auth::id();

        $encuesta = Encuesta::findOrFail($encuestaId);

        $dimensiones     = session("flujo_{$encuestaId}_dimensiones");
        $totalPantallas  = session("flujo_{$encuestaId}_total");
        $sesionId        = session("flujo_{$encuestaId}_sesion");
        $instanciaId     = session("flujo_{$encuestaId}_instancia");

        if (!$dimensiones || !$instanciaId) {
            return redirect()->route('encuestas.flujo.start', $encuestaId);
        }

        if ($grupo < 1 || $grupo > $totalPantallas) {
            abort(404);
        }

        // calcular dimensión/subdimensión por grupo
        $indexGrupo       = $grupo - 1;
        $indexDimension   = intdiv($indexGrupo, 3);
        $indexSubdimension = $indexGrupo % 3;

        $dimensionId = $dimensiones[$indexDimension];
        $dimension = Dimension::findOrFail($dimensionId);

        $subdimension = Subdimension::where('id_dimension', $dimensionId)
            ->orderBy('posicion')
            ->get()
            ->get($indexSubdimension);

        // Respuestas del usuario SOLO del período activo
        $respuestasUsuario = Respuesta::where('id_usuario', $userId)
            ->where('id_encuesta', $encuestaId)
            ->where('id_encuesta_instancia', $instanciaId)
            ->get()
            ->keyBy('id_pregunta');

        // Preguntas base
        $preguntas = $subdimension->preguntas()
            ->where('id_encuesta', $encuestaId)
            ->with('alternativas')
            ->orderBy('posicion')
            ->get()
            ->values();

        $porcentaje = round(($grupo / $totalPantallas) * 100);

        return view('responder_flujo.grupo', compact(
            'encuesta',
            'dimension',
            'subdimension',
            'preguntas',
            'grupo',
            'totalPantallas',
            'porcentaje',
            'respuestasUsuario'
        ));
    }

    public function guardarRespuestaAjax(Request $request, $encuestaId)
    {
        $userId = Auth::id();

        $instanciaId = session("flujo_{$encuestaId}_instancia");
        $sesionId    = session("flujo_{$encuestaId}_sesion");
        $total       = session("flujo_{$encuestaId}_total");

        if (!$instanciaId || !$sesionId) {
            return response()->json(['ok' => false, 'error' => 'Sesión inválida.'], 422);
        }

        $request->validate([
            'pregunta_id'    => 'required|exists:preguntas,id',
            'alternativa_id' => 'required', // puede venir como ID real (dep) o ID normal
            'grupo'          => 'required|integer',
            'valor'          => 'nullable|integer', // para SI/NO dependiente enviamos 1/0
        ]);

        $preguntaId = (int) $request->pregunta_id;
        $altId      = (int) $request->alternativa_id;
        $grupo      = (int) $request->grupo;

        // valor: si viene del frontend (dependiente SI/NO), se usa; si no, se consulta el valor real de la alternativa
        if ($request->has('valor') && $request->valor !== null) {
            $valor = (int) $request->valor;
        } else {
            $alt = Alternativa::find($altId);
            $valor = $alt ? (int)$alt->valor : 0;
        }

        // Guardar sin duplicados por (encuesta, instancia, usuario, pregunta)
        $condicion = [
            'id_encuesta'          => (int)$encuestaId,
            'id_encuesta_instancia'=> (int)$instanciaId,
            'id_usuario'           => (int)$userId,
            'id_pregunta'          => (int)$preguntaId,
        ];

        $valores = [
            'id_alternativa' => (int)$altId,
            'valor'          => (int)$valor,
            'nivel'          => 1,
        ];

        $resp = Respuesta::updateOrCreate($condicion, $valores);

        // guardar avance (reanudación)
        $sesion = EncuestasUsuario::find($sesionId);
        if ($sesion) {
            $sesion->ultimo_grupo = $grupo;
            $sesion->save();
        }

        $porcentaje = $total ? round(($grupo / $total) * 100) : 0;

        return response()->json([
            'ok' => true,
            'porcentaje' => $porcentaje,
            'respuesta_id' => $resp->id,
        ]);
    }

    public function finalizar($encuestaId)
    {
        $userId = Auth::id();

        $instanciaId = session("flujo_{$encuestaId}_instancia");
        $sesionId    = session("flujo_{$encuestaId}_sesion");

        if (!$instanciaId || !$sesionId) {
            return redirect()->route('encuestas.flujo.start', $encuestaId);
        }

        $sesion = EncuestasUsuario::where('id', $sesionId)
            ->where('id_usuario', $userId)
            ->where('id_encuesta', $encuestaId)
            ->where('id_encuesta_instancia', $instanciaId)
            ->first();

        if ($sesion) {
            $sesion->completado = 1;
            $sesion->save();
        }

        // Limpieza de sesión de flujo
        session()->forget([
            "flujo_{$encuestaId}_dimensiones",
            "flujo_{$encuestaId}_total",
            "flujo_{$encuestaId}_sesion",
            "flujo_{$encuestaId}_instancia",
        ]);

        return redirect()->route('responder.index')
            ->with('success', 'Encuesta finalizada correctamente.');
    }
}