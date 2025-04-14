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
use Illuminate\View\View;

class ResponderController extends Controller
{
    public function index(Request $request): View
    {
        $iduser = auth()->user()->id;
        $respondido = EncuestasUsuario::select('id_encuesta')->where('id_usuario',$iduser)->get();
        foreach($respondido as $item){
            $excluir[] = $item->id_encuesta;
        }
        $encuestas = Encuesta::all()->whereNotIn('id','21');
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
    public function iniciar(Encuesta $encuesta)
    {
        $pregunta = $encuesta->preguntas()->orderBy('posicion')->first();
        if (!$pregunta) {
            return redirect()->route('home')->with('error', 'No hay preguntas para esta encuesta');
        }
        return view('responder.responder', compact('encuesta', 'pregunta'));
    }

    public function responder(Request $request, Encuesta $encuesta, Pregunta $pregunta)
    {
        // Validar y guardar
        $respuesta = new Respuesta();
        $respuesta->id_pregunta = $pregunta->id;
        $respuesta->id_alternativa = $request->input('alternativa_id');
        $respuesta->valor = $request->input('valor', 0); // opcional
        $respuesta->texto = $request->input('texto', '');
        $respuesta->nivel = 1; // puedes personalizar esto
        $respuesta->save();

        // Obtener la siguiente pregunta
        $siguiente = $encuesta->preguntas()
            ->where('posicion', '>', $pregunta->posicion)
            ->orderBy('posicion')
            ->first();

        if ($siguiente) {
            return redirect()->route('responder.iniciar', $encuesta->id)
                             ->with('pregunta_id', $siguiente->id);
        }

        return redirect()->route('home')->with('success', 'Encuesta completada');
    }

}
