<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Pregunta;
use App\Models\Alternativa;
use App\Models\Respuesta;
use App\Models\CabeceraPregunta;
use App\Models\CabeceraAlternativa;
use App\Models\CabeceraRespuesta;
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
        $iduser = Auth::user()->id;
        $respondido = EncuestasUsuario::select('id_encuesta')->where('id_usuario',$iduser)->get();
        foreach($respondido as $item){
            $excluir[] = $item->id_encuesta;
        }
        $encuestas = Encuesta::all();
        return view('responder.index', compact('encuestas','respondido'));
            //->with('i', ($request->input('page', 1) - 1) * $encuestas->perPage());
    }

    public function show($id): View
    {
        $encuesta = Encuesta::find($id);
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
            ->leftjoin('subdimensiones','subdimensiones.id','=','preguntas.id_subdimension')
            ->where('preguntas.id_encuesta', $id)
            ->get();
        $cabeceras = CabeceraPregunta::all()->where('id_encuesta', $id);

        foreach($preguntas as $indice => $preg){
            $idp[] = $preg->id;
        }
        if (isset($idp)){
            $alternativas = Alternativa::all()->whereIn('id_pregunta', $idp);
        } else {
            $alternativas = null;
        }

        foreach ($cabeceras as $item => $valor) {
            $ida[] = $valor->id;
        }

        if (isset($ida)) {
            $cabalternativas = CabeceraAlternativa::all()->whereIn('id_cabecera', $ida);
        } else {
            $cabalternativas = null;
        }
        return view('responder.show', compact('encuesta','preguntas','alternativas','cabeceras','cabalternativas', 'dimensiones', 'subdimensiones'));
    }

    public function save(Request $request)
    {
        $id_encuesta = $request->id_encuesta;

        $preguntas = Pregunta::all()->where('id_encuesta',$id_encuesta);
        $cabeceras = CabeceraPregunta::all()->where('id_encuesta', $id_encuesta);

        foreach($cabeceras as $cab){
            $cab->id;
            $calter = "cabresp".$cab->id;
            $cabeza = new CabeceraRespuesta();
            $cabeza->id_pregunta = $cab->id;
            if ($cab->tipo==2){
                $cabeza->respuesta = $request->$calter;
                $cabeza->id_alternativa = '0';
            } else {
                $cabeza->respuesta = "";
                $cabeza->id_alternativa = $request->$calter;
            }
            $cabeza->save();
        }

        foreach($preguntas as $preg){
            $preg->id;
            $alter = "respuesta".$preg->id;
            $dat = explode('|',$request->$alter);
            $respuesta = new Respuesta();
            $respuesta->id_pregunta = $preg->id;
            $respuesta->id_alternativa = $dat[0];
            $respuesta->valor = $dat[1];
            $respuesta->nivel = '1';
            $respuesta->save();
        }

        $responde = new EncuestasUsuario();
        $responde->id_encuesta = $id_encuesta;
        $responde->id_usuario = Auth::user()->id;
        $responde->save();

        return Redirect::route('responder.index')
            ->with('success', 'Respuesta created successfully.');
    }

    /**
     *  INICIO EL RESPONDER ENCUESTA
    */

    public function mostrar_old($idEncuesta, $index = 0)
    {
        $encuesta = Encuesta::findOrFail($idEncuesta);
        $usuario = Auth::user()->id;

        $registro = EncuestasUsuario::where('id_encuesta',$idEncuesta)
            ->where('id_usuario',$usuario)
            ->first();

        if (!$registro){
            EncuestasUsuario::create([
                'id_encuesta' => $idEncuesta,
                'id_usuario'  => $usuario,
            ]);
        }

        $preguntas = Pregunta::select('preguntas.*', 'd.nombre as dimension', 'sd.nombre as subdimension')
            ->leftJoin('subdimensiones as sd', 'sd.id', '=', 'preguntas.id_subdimension')
            ->leftJoin('dimensiones as d', 'd.id', '=', 'sd.id_dimension')
            ->where('id_encuesta', 1)
            ->orderBy('id_subdimension')
            ->orderBy('posicion', 'asc')
            ->get();
        
        $totalPreguntas = count($preguntas);

        // Obtener las respuestas que ha dado el usuario a esta encuesta
        $preguntasRespondidas = Respuesta::whereIn('id_pregunta', $preguntas->pluck('id'))
            ->where('id_usuario', $usuario)
            ->pluck('id_pregunta')
            ->toArray();
        $index=count($preguntasRespondidas);

        if ($index==$totalPreguntas){
            return redirect()->route('responder.index')->with('success', 'La encuesta ya fue contestada');
        }
 
        if($preguntas[$index]->id_dependencia!=0){
            $depende = $preguntas[$index]->id_dependencia;
            $pregAct = $preguntas[$index]->id;
            $pregAnt = $preguntas[$index-1]->id;
            $respAnt = Respuesta::where('id_pregunta',$pregAnt)->where('id_usuario', $usuario)->pluck('id_alternativa');
            $alter = Alternativa::where('id_pregunta',$pregAct)->where('id_dependencia', $respAnt)->get();    

            $countAlter = count($alter);

            if ($countAlter>0){
                $preguntas[$index]->texto = $alter[0]->texto;
                $preguntas[$index]->alternativas[0]->texto = "Si";
                $preguntas[$index]->alternativas[0]->valor = 1;
                $preguntas[$index]->alternativas[0]->id = $alter[0]->id;
                $preguntas[$index]->alternativas[1]->texto = "No";
                $preguntas[$index]->alternativas[1]->valor = 0;
                $preguntas[$index]->alternativas[1]->id = $alter[0]->id;
            } else {
                Respuesta::create([
                    'id_pregunta' => $pregAct,
                    'id_alternativa' => 0,
                    'id_usuario' => $usuario,
                    'valor' => 0,
                    'nivel' => $preguntas[$index]->id_subdimension,
                ]);
                $index = $index + 1;
            }

        }
        // Si no hay más preguntas, redirigimos
        if (!isset($preguntas[$index])) {
            return redirect()->route('responder.index')->with('success', 'Gracias por responder la encuesta.');
        }
        $pregunta = $preguntas[$index];
        return view('responder.mostrar', compact('encuesta', 'pregunta', 'index', 'totalPreguntas'));
    }

    public function guardar(Request $request)
    {
        $usuario = Auth::user()->id;
        Respuesta::create([
            'id_encuesta' => $request->id_encuesta,
            'id_pregunta' => $request->id_pregunta,
            'id_alternativa' => $request->id_alternativa,
            'id_usuario' => $usuario,
            'valor' => $request->valor,
            'nivel' => $request->nivel,
        ]);
        // Aumentar el índice y redirigir
        $siguienteIndex = $request->index + 1;
        return response()->json([
            'siguiente_url' => route('responder.mostrar', [
                'idEncuesta' => $request->id_encuesta,
                'index' => $siguienteIndex
            ])
        ]);
    }
    /**
     * FIN
     */

    public function mostrar($idEncuesta)
    {
        $encuesta = Encuesta::findOrFail($idEncuesta);

        $usuario = Auth::user()->id;

        $registro = EncuestasUsuario::where('id_encuesta',$idEncuesta)
            ->where('id_usuario',$usuario)
            ->first();

        if (!$registro){
            EncuestasUsuario::create([
                'id_encuesta' => $idEncuesta,
                'id_usuario'  => $usuario,
            ]);
        }

        $subdimensiones = Pregunta::join('subdimensiones', 'subdimensiones.id', '=', 'preguntas.id_subdimension')
            ->where('preguntas.id_encuesta', 1)
            ->select('preguntas.id_subdimension', 'subdimensiones.nombre')
            ->distinct()
            ->get();

        $gruposDePreguntas = collect();

        foreach ($subdimensiones as $subdimension) {
            $subdimension = Subdimension::find($subdimension->id_subdimension);
            if (!$subdimension) {
                continue;
            }

            $subid = $subdimension->id;

            $preguntas = Pregunta::select('preguntas.*', 'd.nombre as dimension','d.descripcion as ddescripcion', 'sd.nombre as subdimension')
                ->leftJoin('subdimensiones as sd', 'sd.id', '=', 'preguntas.id_subdimension')
                ->leftJoin('dimensiones as d', 'd.id', '=', 'sd.id_dimension')
                ->where('id_encuesta', 1)
                ->where('sd.id',$subid)
                ->orderBy('posicion', 'asc')
                ->get();

            if ($preguntas->isNotEmpty()) {

                $gruposDePreguntas->push([
                    'dimension' => $preguntas[0]->dimension,
                    'dimension_descripcion' => $preguntas[0]->ddescripcion,
                    'subdimension_id' => $subdimension->id,
                    'subdimension_nombre' => $subdimension->nombre,
                    'subdimension_descripcion' => $subdimension->descripcion,
                    'preguntas' => $preguntas,
                ]);
            }
        }
        return view('responder.mostrar', [
            'encuesta' => $encuesta,
            'gruposDePreguntas' => $gruposDePreguntas,
            'totalGrupos' => $gruposDePreguntas->count(),
        ]);
    }

 public function guardarRespuestasGrupo(Request $request)
    {
        $validatedData = $request->validate([
            'encuesta_id' => 'required|integer|exists:encuestas,id',
            'respuestas' => 'sometimes|array', // 'sometimes' si un grupo podría no tener preguntas respondibles
            'respuestas.*.pregunta_id' => 'required|integer|exists:preguntas,id',
            'respuestas.*.alternativa_id' => 'nullable|integer|exists:alternativas,id',
            'respuestas.*.valor_texto' => 'nullable|string|max:65535', // Para respuestas de texto abierto
        ]);

        $userId = Auth::id();
        if (!$userId) {
            // Considera si permitir respuestas anónimas o requerir inicio de sesión.
            // Si se permiten anónimas, necesitarás una forma de identificar al encuestado (ej. session ID).
            return response()->json(['success' => false, 'message' => 'Usuario no autenticado.'], 401);
        }

        DB::beginTransaction();
        try {
            if (!empty($validatedData['respuestas'])) {
                foreach ($validatedData['respuestas'] as $respuestaData) {
                    $pregunta = Pregunta::find($respuestaData['pregunta_id']);
                    if (!$pregunta) {
                        continue; // Saltar si la pregunta no existe
                    }

                    $datosAGuardar = [
                        'id_alternativa' => null,
                        'valor' => null,          // Para el valor numérico de la alternativa
                        'respuesta_texto' => null, // Para la respuesta de texto (requiere columna en DB)
                        // 'nivel' => null,       // Calcular si es necesario
                    ];

                    if (!empty($respuestaData['alternativa_id'])) {
                        $alternativa = Alternativa::find($respuestaData['alternativa_id']);
                        if ($alternativa) {
                            $datosAGuardar['id_alternativa'] = $alternativa->id;
                            $datosAGuardar['valor'] = $alternativa->valor; // Asume que 'alternativas.valor' es el valor numérico
                        }
                    } elseif (isset($respuestaData['valor_texto'])) {
                        // Guarda el texto en la columna 'respuesta_texto'.
                        // Asegúrate que esta columna exista en tu tabla 'respuestas' y sea TEXT/VARCHAR.
                        $datosAGuardar['respuesta_texto'] = $respuestaData['valor_texto'];
                    }
                    
                    // Lógica para el campo 'nivel' si es necesario. Ejemplo:
                    // if ($datosAGuardar['valor'] !== null) {
                    //    if ($datosAGuardar['valor'] >= 4) $datosAGuardar['nivel'] = 3; // Alto
                    //    elseif ($datosAGuardar['valor'] >= 2) $datosAGuardar['nivel'] = 2; // Medio
                    //    else $datosAGuardar['nivel'] = 1; // Bajo
                    // }


                    Respuesta::updateOrCreate(
                        [
                            'id_encuesta' => $validatedData['encuesta_id'],
                            'id_pregunta' => $respuestaData['pregunta_id'],
                            'id_usuario' => $userId,
                        ],
                        $datosAGuardar
                    );
                }
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Respuestas guardadas correctamente.']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error guardando respuestas de grupo: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return response()->json(['success' => false, 'message' => 'Error interno al guardar las respuestas.'], 500);
        }
    }

    /**
     * Fin Responder por subdimension
     */
}

