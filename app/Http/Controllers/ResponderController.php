<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Pregunta;
use App\Models\Alternativa;
use App\Models\Respuesta;
use App\Models\CabeceraPregunta;
use App\Models\CabeceraAlternativa;
use App\Models\CabeceraRespuesta;
use App\Models\EncuestaInstancia;
use App\Models\EncuestasUsuario;
use App\Models\Dimension;
use App\Models\Subdimension;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ResponderController extends Controller
{
    public function index(Request $request): View
    {
        $encuestas = Encuesta::where('estado', 1)->with('linea')->get();
        return view('responder.index', compact('encuestas'));
    }

    public function show($id): View
    {
        $encuesta = Encuesta::findOrFail($id);
        $this->authorize('respond', $encuesta);
        $dimensiones = Dimension::all();
        $subdimensiones = Subdimension::all();

        $preguntas = Pregunta::select(
                'preguntas.id',
                'preguntas.id_encuesta',
                'preguntas.id_subdimension',
                'preguntas.texto',
                'preguntas.tipo',
                'preguntas.id_dependencia',
                'preguntas.created_at',
                'preguntas.updated_at',
                'subdimensiones.nombre'
            )
            ->leftjoin('subdimensiones', 'subdimensiones.id', '=', 'preguntas.id_subdimension')
            ->where('preguntas.id_encuesta', $id)
            ->get();

        $cabeceras = CabeceraPregunta::where('id_encuesta', $id)->get();

        $idp = $preguntas->pluck('id')->toArray();

        $alternativas = !empty($idp)
            ? Alternativa::whereIn('id_pregunta', $idp)->get()
            : collect();

        $ida = $cabeceras->pluck('id')->toArray();

        $cabalternativas = !empty($ida)
            ? CabeceraAlternativa::whereIn('id_cabecera', $ida)->get()
            : collect();

        return view('responder.show', compact(
            'encuesta',
            'preguntas',
            'alternativas',
            'cabeceras',
            'cabalternativas',
            'dimensiones',
            'subdimensiones'
        ));
    }

    /**
     * Guardado "antiguo" (no flujo). Se deja, pero OJO: no usa períodos.
     * Si ya migraste al flujo por período, idealmente no usar este método.
     */
    public function save(Request $request)
    {
        $request->validate([
            'id_encuesta' => 'required|integer|exists:encuestas,id',
        ]);

        $encuesta = Encuesta::findOrFail($request->id_encuesta);
        $this->authorize('respond', $encuesta);

        $id_encuesta = (int) $request->id_encuesta;

        $preguntas = Pregunta::where('id_encuesta', $id_encuesta)->get();
        $cabeceras = CabeceraPregunta::where('id_encuesta', $id_encuesta)->get();

        DB::beginTransaction();
        try {
            foreach ($cabeceras as $cab) {
                $calter = "cabresp" . $cab->id;

                $cabeza = new CabeceraRespuesta();
                $cabeza->id_pregunta = $cab->id;

                if ($cab->tipo == 2) {
                    $cabeza->respuesta = $request->$calter ?? '';
                    $cabeza->id_alternativa = 0;
                } else {
                    $cabeza->respuesta = '';
                    $cabeza->id_alternativa = $request->$calter ?? 0;
                }

                $cabeza->save();
            }

            foreach ($preguntas as $preg) {
                $alter = "respuesta" . $preg->id;

                if (!$request->has($alter)) {
                    continue;
                }

                $valorRaw = $request->$alter;
                $dat = explode('|', $valorRaw);

                if (count($dat) < 2) {
                    continue;
                }

                $respuesta = new Respuesta();
                $respuesta->id_pregunta = $preg->id;
                $respuesta->id_alternativa = (int) ($dat[0] ?? 0);
                $respuesta->valor = (int) ($dat[1] ?? 0);
                $respuesta->nivel = 1;
                $respuesta->id_encuesta = $id_encuesta;
                $respuesta->id_usuario = Auth::id();
                $respuesta->save();
            }

            DB::commit();

            return Redirect::route('responder.index')
                ->with('success', 'Respuesta creada correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error guardando respuestas (save antiguo): ' . $e->getMessage());
            return Redirect::route('responder.index')
                ->with('error', 'Error al guardar las respuestas.');
        }
    }

    /**
     * FLUJO: Mostrar preguntas agrupadas por subdimensión, con reanudación y bloqueo por período activo
     */
    public function mostrar($idEncuesta)
    {
        $user = Auth::user();
        $usuarioId = $user->id;

        $encuesta = Encuesta::findOrFail($idEncuesta);
        $this->authorize('respond', $encuesta);

        // 1) Obtener período activo para esta encuesta
        $hoy = now()->toDateString();

        $instancia = EncuestaInstancia::where('id_encuesta', $idEncuesta)
            ->where('estado', 1)
            ->whereDate('fecha_desde', '<=', $hoy)
            ->whereDate('fecha_hasta', '>=', $hoy)
            ->orderByDesc('id')
            ->first();

        if (!$instancia) {
            abort(403, 'Esta encuesta no tiene un Período de aplicación activo hoy.');
        }

        $instanciaId = (int) $instancia->id;

        // 2) Crear/retomar sesión por (encuesta, instancia, usuario)
        $registro = EncuestasUsuario::firstOrCreate(
            [
                'id_encuesta' => $idEncuesta,
                'id_encuesta_instancia' => $instanciaId,
                'id_usuario'  => $usuarioId,
            ],
            [
                'ultima_pregunta_id' => null,
                'completado' => 0,
                'ultimo_grupo' => 1,
                // Compatibilidad: si tu tabla aún tiene id_instancia NOT NULL
                'id_instancia' => $instanciaId,
            ]
        );

        // 3) Bloqueo: si ya completó en este período, no puede entrar
        if ((int)$registro->completado === 1) {
            abort(403, 'Usted ya respondió esta encuesta en el Período de aplicación actual.');
        }

        // 4) Reanudar avance (por período)
        $avance = (int) ($registro->ultimo_grupo ?? 1);
        if ($avance > 1) {
            return redirect()->route('responder.mostrarGrupo', [
                'id_encuesta' => $idEncuesta,
                'grupo' => $avance
            ]);
        }

        // 5) Construir subdimensiones permitidas (lógica por rol desde config)
        $loger = $user->load('roles');
        $permiso = $loger->roles->pluck('nombre');
        $rol = $permiso[0] ?? null;

        $configDimensiones = config('rol_dimensiones', []);
        $dimensionesPermitidas = null;

        $hospitalRoles = $configDimensiones['hospital_roles'] ?? [];
        if (isset($hospitalRoles[$rol])) {
            $dimensionesPermitidas = $hospitalRoles[$rol];
        }

        $gestionRoles = $configDimensiones['gestion_roles'] ?? [];
        if (isset($gestionRoles[$rol])) {
            $dimensionesPermitidas = $gestionRoles[$rol];
        }

        $fullAccessRoles = $configDimensiones['full_access_roles'] ?? [];
        if (in_array($rol, $fullAccessRoles)) {
            $dimensionesPermitidas = [];
        }

        $query = Pregunta::join('subdimensiones as sd', 'sd.id', '=', 'preguntas.id_subdimension')
            ->leftJoin('dimensiones as d', 'd.id', '=', 'sd.id_dimension')
            ->where('preguntas.id_encuesta', $idEncuesta)
            ->select([
                'sd.id',
                'd.id as did',
                'd.nombre as dnombre',
                'd.descripcion as ddescrip',
                'preguntas.id_subdimension',
                'sd.nombre',
                'sd.descripcion'
            ])
            ->distinct();

        if ($dimensionesPermitidas !== null && !empty($dimensionesPermitidas)) {
            $query->whereIn('d.id', $dimensionesPermitidas);
        }

        $subdimensiones = $query->get();

        // 6) Construir grupos con preguntas, pero filtrando respuestaUsuario por instancia
        $subdimensionesIds = $subdimensiones->pluck('id')->unique();

        $preguntasPorSubdimension = Pregunta::where('id_encuesta', $idEncuesta)
            ->whereIn('id_subdimension', $subdimensionesIds)
            ->with(['alternativas', 'respuestaUsuario'])
            ->orderBy('posicion')
            ->get()
            ->groupBy('id_subdimension');

        $gruposDePreguntas = collect();

        foreach ($subdimensiones as $subdimension) {

            $subid = $subdimension->id;

            $preguntas = $preguntasPorSubdimension->get($subid, collect());

            if ($preguntas->isNotEmpty()) {
                $gruposDePreguntas->push([
                    'dimension' => $subdimension->dnombre,
                    'dimension_descripcion' => $subdimension->ddescrip,
                    'subdimension_id' => $subdimension->id,
                    'subdimension_nombre' => $subdimension->nombre,
                    'subdimension_descripcion' => $subdimension->descripcion,
                    'preguntas' => $preguntas,
                ]);
            }
        }

        // Guardar instancia en sesión para el flujo (opcional pero útil)
        session([
            "encuesta_{$idEncuesta}_instancia" => $instanciaId,
            "encuesta_{$idEncuesta}_sesion" => $registro->id,
        ]);

        return view('responder.mostrar', [
            'encuesta' => $encuesta,
            'gruposDePreguntas' => $gruposDePreguntas,
            'totalGrupos' => $gruposDePreguntas->count(),
            'instanciaId' => $instanciaId,
        ]);
    }

    public function guardarRespuestasGrupo(Request $request)
    {
        $validatedData = $request->validate([
            'encuesta_id' => 'required|integer|exists:encuestas,id',
            'grupo_actual' => 'required|integer',
            'respuestas' => 'sometimes|array',
            'respuestas.*.pregunta_id' => 'required|integer|exists:preguntas,id',
            'respuestas.*.alternativa_id' => 'nullable|integer|exists:alternativas,id',
            'respuestas.*.valor_texto' => 'nullable|string|max:65535',
        ]);

        $encuesta = Encuesta::findOrFail($validatedData['encuesta_id']);
        $this->authorize('respond', $encuesta);

        $userId = Auth::id();
        if (!$userId) {
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
        }

        $encuestaId = (int)$validatedData['encuesta_id'];

        // Período activo desde sesión o por DB (fallback)
        $instanciaId = session("encuesta_{$encuestaId}_instancia");

        if (!$instanciaId) {
            $hoy = now()->toDateString();
            $instancia = EncuestaInstancia::where('id_encuesta', $encuestaId)
                ->where('estado', 1)
                ->whereDate('fecha_desde', '<=', $hoy)
                ->whereDate('fecha_hasta', '>=', $hoy)
                ->orderByDesc('id')
                ->first();

            if (!$instancia) {
                return response()->json(['success' => false, 'message' => 'No existe un Período de aplicación activo.'], 403);
            }

            $instanciaId = (int)$instancia->id;
            session(["encuesta_{$encuestaId}_instancia" => $instanciaId]);
        }

        // Sesión por período
        $sesion = EncuestasUsuario::firstOrCreate(
            [
                'id_encuesta' => $encuestaId,
                'id_encuesta_instancia' => $instanciaId,
                'id_usuario' => $userId,
            ],
            [
                'ultima_pregunta_id' => null,
                'completado' => 0,
                'ultimo_grupo' => 1,
                'id_instancia' => $instanciaId, // compatibilidad si existe
            ]
        );

        if ((int)$sesion->completado === 1) {
            return response()->json(['success' => false, 'message' => 'Encuesta ya completada en el período actual.'], 403);
        }

        DB::beginTransaction();
        try {
            if (!empty($validatedData['respuestas'])) {
                $preguntasValidas = Pregunta::where('id_encuesta', $encuestaId)
                    ->pluck('id')
                    ->toArray();

                foreach ($validatedData['respuestas'] as $respuestaData) {

                    $preguntaId = (int)$respuestaData['pregunta_id'];

                    if (!in_array($preguntaId, $preguntasValidas)) {
                        continue;
                    }

                    $datosAGuardar = [
                        'id_encuesta' => $encuestaId,
                        'id_encuesta_instancia' => $instanciaId,
                        'id_instancia' => $instanciaId, // compatibilidad si tu tabla lo exige
                        'id_pregunta' => $preguntaId,
                        'id_usuario' => $userId,

                        // campos base
                        'id_alternativa' => 0,
                        'valor' => 0,
                        'nivel' => 1,

                        // si tu tabla tiene campos extra (ej. respuesta_texto) y no existe en DB, no lo seteamos
                    ];

                    // Alternativa elegida
                    if (!empty($respuestaData['alternativa_id'])) {
                        $altId = (int)$respuestaData['alternativa_id'];
                        $alternativa = Alternativa::find($altId);
                        if ($alternativa) {
                            $datosAGuardar['id_alternativa'] = $alternativa->id;
                            $datosAGuardar['valor'] = (int)$alternativa->valor; // valor real de la alternativa
                        }
                    }

                    // Texto abierto (si aplicara en tu BD; si NO existe la columna, no lo uses)
                    // Si en tu proyecto existe respuesta_texto, descomenta y agrega al fillable/DB.
                    // if (isset($respuestaData['valor_texto'])) {
                    //     $datosAGuardar['respuesta_texto'] = $respuestaData['valor_texto'];
                    // }

                    Respuesta::updateOrCreate(
                        [
                            'id_encuesta' => $encuestaId,
                            'id_encuesta_instancia' => $instanciaId,
                            'id_pregunta' => $preguntaId,
                            'id_usuario' => $userId,
                        ],
                        $datosAGuardar
                    );

                    // avance en la sesión del período
                    $sesion->ultima_pregunta_id = $preguntaId;
                }
            }

            // Guardar grupo actual
            $sesion->ultimo_grupo = (int)$validatedData['grupo_actual'];
            $sesion->save();

            DB::commit();

            return response()->json(['success' => true, 'message' => 'Respuestas guardadas correctamente.']);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error guardando respuestas de grupo: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error interno al guardar las respuestas.'], 500);
        }
    }
}