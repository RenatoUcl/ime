<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Dimension;
use App\Models\Subdimension;
use App\Models\EncuestasUsuario;
use App\Models\EncuestaInstancia;
use App\Models\EncuestaUsuarioDimension;
use App\Models\Respuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncuestaFlujoController extends Controller
{
    /**
     * Inicio del flujo
     */
    public function start($encuestaId)
    {
        $userId = Auth::id();
        $encuesta = Encuesta::findOrFail($encuestaId);

        // 1. Buscar instancia activa por fecha
        $instancia = EncuestaInstancia::where('id_encuesta', $encuestaId)
            ->whereDate('fecha_desde', '<=', today())
            ->whereDate('fecha_hasta', '>=', today())
            ->where('estado', 1)
            ->first();

        if (!$instancia) {
            abort(403, "No hay una instancia activa de esta encuesta.");
        }

        // 2. Permisos por dimensión
        $dimensionesPermitidas = EncuestaUsuarioDimension::where('id_usuario', $userId)
            ->where('id_encuesta', $encuestaId)
            ->orderBy('orden')
            ->pluck('id_dimension')
            ->toArray();

        if (empty($dimensionesPermitidas)) {
            abort(403, "No tiene permiso para responder esta encuesta.");
        }

        // 3. Obtener o crear sesión de usuario por instancia
        $sesion = EncuestasUsuario::firstOrCreate([
            'id_encuesta'  => $encuestaId,
            'id_instancia' => $instancia->id,
            'id_usuario'   => $userId,
        ], [
            'ultima_pregunta_id' => null,
            'ultimo_grupo'       => 1,
            'completado'         => 0
        ]);

        $totalPantallas = count($dimensionesPermitidas) * 3;

        session([
            "flujo_{$encuestaId}_dimensiones" => $dimensionesPermitidas,
            "flujo_{$encuestaId}_total" => $totalPantallas,
            "flujo_{$encuestaId}_sesion" => $sesion->id,
            "flujo_{$encuestaId}_instancia" => $instancia->id,
        ]);

        return redirect()->route('encuestas.flujo.grupo', [
            'encuestaId' => $encuestaId,
            'grupo' => $sesion->ultimo_grupo,
        ]);
    }

    /**
     * Mostrar grupo/pantalla
     */
    public function showGrupo($encuestaId, $grupo)
    {
        $userId = Auth::id();
        $encuesta = Encuesta::findOrFail($encuestaId);

        $dimensiones = session("flujo_{$encuestaId}_dimensiones");
        $totalPantallas = session("flujo_{$encuestaId}_total");
        $instanciaId = session("flujo_{$encuestaId}_instancia");

        if (!$dimensiones) {
            return redirect()->route('encuestas.flujo.start', $encuestaId);
        }

        if ($grupo < 1 || $grupo > $totalPantallas) {
            abort(404);
        }

        // Cálculo de posición
        $indexGrupo = $grupo - 1;
        $indexDimension = intdiv($indexGrupo, 3);
        $indexSubdimension = $indexGrupo % 3;

        $dimensionId = $dimensiones[$indexDimension];

        $dimension = Dimension::findOrFail($dimensionId);

        $subdimension = Subdimension::where('id_dimension', $dimensionId)
            ->orderBy('posicion')
            ->get()
            ->get($indexSubdimension);

        // Respuestas del usuario por instancia
        $respuestasUsuario = Respuesta::where('id_usuario', $userId)
            ->where('id_encuesta', $encuestaId)
            ->where('id_instancia', $instanciaId)
            ->get()
            ->keyBy('id_pregunta');

        // Cargar preguntas de la subdimensión
        $preguntas = $subdimension->preguntas()
            ->where('id_encuesta', $encuestaId)
            ->with('alternativas')
            ->orderBy('posicion')
            ->get()
            ->map(function ($preg) use ($respuestasUsuario) {

                // Es dependiente?
                if ($preg->id_dependencia && $preg->id_dependencia != 0) {

                    $respuestaPadre = $respuestasUsuario->get($preg->id_dependencia);

                    if (!$respuestaPadre) {
                        return null;
                    }

                    $alt = $preg->alternativas
                        ->firstWhere('id_dependencia', $respuestaPadre->id_alternativa);

                    if (!$alt) {
                        return null;
                    }

                    // Pregunta dependiente: texto + SI/NO
                    $preg->texto = $alt->texto . "<br>" . $preg->texto;

                    $preg->alternativas = collect([
                        (object)[ 'id' => $preg->id . '_1', 'texto' => 'SI', 'valor' => 1 ],
                        (object)[ 'id' => $preg->id . '_0', 'texto' => 'NO', 'valor' => 0 ],
                    ]);
                }

                return $preg;
            })
            ->filter()
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

    /**
     * Guardado vía AJAX con instancia
     */
    public function guardarRespuestaAjax(Request $request, $encuestaId)
    {
        $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'alternativa_id' => 'required',
            'grupo' => 'required|integer',
        ]);

        $instanciaId = session("flujo_{$encuestaId}_instancia");

        $alternativaBruta = $request->alternativa_id;

        // Si la alternativa es dependiente: formato "idAlternativa_valor"
        if (strpos($alternativaBruta, '_') !== false) {
            [$idAlternativa, $valor] = explode('_', $alternativaBruta);
            $idAlternativa = (int)$idAlternativa;
            $valor = (int)$valor;
        } else {
            $idAlternativa = (int)$alternativaBruta;
            $alt = \App\Models\Alternativa::find($idAlternativa);
            $valor = $alt ? $alt->valor : 0;
        }

        // Clave única por instancia
        $condicion = [
            'id_encuesta' => $encuestaId,
            'id_instancia' => $instanciaId,
            'id_pregunta' => $request->pregunta_id,
            'id_usuario' => Auth::id(),
        ];

        $valores = [
            'id_alternativa' => $idAlternativa,
            'valor' => $valor,
            'nivel' => 1
        ];

        Respuesta::updateOrCreate($condicion, $valores);

        return response()->json([
            'ok' => true,
            'message' => 'Guardado'
        ]);
    }

    /**
     * Finalizar encuesta
     */
    public function finalizar($encuestaId)
    {
        $instanciaId = session("flujo_{$encuestaId}_instancia");

        EncuestasUsuario::where('id_usuario', Auth::id())
            ->where('id_encuesta', $encuestaId)
            ->where('id_instancia', $instanciaId)
            ->update([ 'completado' => 1 ]);

        return redirect()->route('home')
            ->with('success', 'Encuesta completada.');
    }
}
