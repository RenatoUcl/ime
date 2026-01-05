<?php

namespace App\Http\Controllers;

use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\Alternativa;
use App\Models\Encuesta;
use App\Models\Subdimension;

use App\Http\Requests\PreguntaRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class PreguntaController extends Controller
{
    public function index(Request $request): View
    {
        $preguntas = Pregunta::paginate();

        return view('pregunta.index', compact('preguntas'))
            ->with('i', ($request->input('page', 1) - 1) * $preguntas->perPage());
    }

    public function create(): View
    {
        $pregunta = new Pregunta();
        return view('pregunta.create', compact('pregunta'));
    }

    public function store(PreguntaRequest $request): RedirectResponse
    {
        Pregunta::create($request->validated());

        return Redirect::route('pregunta.index')
            ->with('success', 'Pregunta created successfully.');
    }

    public function show($id): View
    {
        $pregunta = Pregunta::find($id);

        return view('pregunta.show', compact('pregunta'));
    }

    /*
    public function edit($id): View
    {
        $pregunta = Pregunta::find($id);
        $encuesta = Encuesta::select('id','nombre','descripcion','estado','created_at','updated_at')
                        ->where('id',$pregunta->id_encuesta)
                        ->get();
        $subdimensiones = Subdimension::all();
        $preguntas = Pregunta::all();
        return view('pregunta.edit', compact('pregunta','subdimensiones','encuesta','preguntas'));
    }
    */

    public function edit($id): View
    {
        $pregunta = Pregunta::findOrFail($id);

        $encuesta = Encuesta::select('id','nombre')
            ->where('id', $pregunta->id_encuesta)
            ->first();

        $subdimensiones = Subdimension::all();

        // Solo preguntas de la misma encuesta
        // Excluye la pregunta actual
        $preguntas = Pregunta::where('id_encuesta', $pregunta->id_encuesta)
            ->where('id', '!=', $pregunta->id)
            ->orderBy('posicion')
            ->get();

        return view('pregunta.edit', compact(
            'pregunta',
            'subdimensiones',
            'encuesta',
            'preguntas'
        ));
    }
    
    public function update(Request $request, $pregunta): RedirectResponse
    {
        $pregunta = Pregunta::find($request->id);
        $pregunta->id_encuesta = $request->id_encuesta;
        $pregunta->id_subdimension = $request->id_subdimension;
        $pregunta->texto = $request->texto;
        $pregunta->tipo = $request->tipo;
        $pregunta->posicion = $request->posicion;
        if ($request->tipo==2){
            $pregunta->id_dependencia = 0;
        } else  {
            $pregunta->id_dependencia = $request->dependede;
        }
        $pregunta->save();

        return Redirect::route('encuesta.edit',$request->id_encuesta)
            ->with('success', 'Pregunta updated successfully');
    }

    public function disabled($id): RedirectResponse
    {
        $pregunta = Pregunta::find($id);
        $respuesta = Respuesta::select('id','id_pregunta')->where('id_pregunta',$id)->get();
        
        if  (count($respuesta)>0){
            return Redirect::route('encuesta.edit',$pregunta->id_encuesta)
            ->with('warning', 'La pregunta no puede ser eliminada, por que contienen respuestas asociadas.');
        } else {
            Alternativa::where('id_pregunta',$id)->delete();
            Pregunta::where('id',$id)->delete();

            return Redirect::route('encuesta.edit',$pregunta->id_encuesta)
            ->with('success', 'La pregunta fue Eliminada.');
        }
    }

    public function destroy($id): RedirectResponse
    {
        Pregunta::find($id)->delete();

        return Redirect::route('pregunta.index')
            ->with('success', 'Pregunta deleted successfully');
    }
}
