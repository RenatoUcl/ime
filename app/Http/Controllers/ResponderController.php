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

use App\Http\Requests\EncuestaRequest;
use App\Http\Requests\PreguntaRequest;
use App\Http\Requests\AlternativaRequest;
use App\Http\Requests\RespuestaRequest;
use App\Http\Requests\CabeceraPreguntaRequest;
use App\Http\Requests\CabeceraAlternativaRequest;
use App\Http\Requests\CabeceraRespuestaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
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
        $responde->id_usuario = auth()->user()->id;
        $responde->save();

        return Redirect::route('responder.index')
            ->with('success', 'Respuesta created successfully.');
    }

    /**
     *
     *  INICIO EL RESPONDER ENCUESTA 
     * 
    */

    public function mostrar($idEncuesta, $index = 0)
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

        $preguntas = Pregunta::where('id_encuesta', $idEncuesta)
            ->with('alternativas')
            ->orderBy('posicion') // o 'id', si no usas posición
            ->get();
    
        // Si no hay más preguntas, redirigimos
        if (!isset($preguntas[$index])) {
            return redirect()->route('responder.index')->with('success', 'Gracias por responder la encuesta.');
        }
    
        $pregunta = $preguntas[$index];
    
        return view('responder.mostrar', compact('encuesta', 'pregunta', 'index'));
    }
    

    public function guardar(Request $request)
    {
        Respuesta::create([
            'id_pregunta' => $request->id_pregunta,
            'id_alternativa' => $request->id_alternativa,
            'valor' => $request->valor,
            'nivel' => 1
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

    public function continuar($id)
    {
        $encuesta = Encuesta::findOrFail($id);

        // Obtener el usuario actual
        $usuarioId = auth()->id();

        // Obtener todas las preguntas de la encuesta ordenadas
        $preguntas = Pregunta::where('id_encuesta', $id)->orderBy('posicion')->get();

        // Obtener las respuestas que ha dado el usuario a esta encuesta
        $preguntasRespondidas = Respuesta::whereIn('id_pregunta', $preguntas->pluck('id'))
            ->where('nivel', $usuarioId)
            ->pluck('id_pregunta')
            ->toArray();

        // Buscar la primera pregunta que no esté respondida
        $preguntaPendiente = $preguntas->first(function ($pregunta) use ($preguntasRespondidas) {
            return !in_array($pregunta->id, $preguntasRespondidas);
        });

        // Si ya respondió todas las preguntas
        if (!$preguntaPendiente) {
            return redirect()->route('responder.index')->with('success', 'Ya has respondido esta encuesta.');
        }

        // Cargar las alternativas para la pregunta pendiente
        $preguntaPendiente->load('alternativas');

        return view('responder.mostrar', compact('encuesta', 'preguntaPendiente'));
    }



    /**
     * FIN
     * 
     */
}
