<?php
namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\Alternativa;
use App\Models\CabeceraAlternativa;
use App\Models\CabeceraPregunta;
use App\Models\Pregunta;
use App\Models\Dimension;
use App\Models\Subdimension;
use App\Models\LineasProgramaticas;

use App\Http\Requests\EncuestaRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EncuestaController extends Controller
{

    public function index(Request $request): View
    {
        $encuestas = Encuesta::paginate();

        return view('encuesta.index', compact('encuestas'))
            ->with('i', ($request->input('page', 1) - 1) * $encuestas->perPage());
    }

    public function create(): View
    {
        $encuesta = new Encuesta();
        $lineas = LineasProgramaticas::all();
        return view('encuesta.create', compact('encuesta','lineas'));
    }

    public function store(EncuestaRequest $request): RedirectResponse
    {
        Encuesta::create($request->validated());
        $lastid = Encuesta::latest()->first()->id;

        return Redirect::route('encuesta.edit', $lastid)
            ->with('success', 'Encuesta created successfully.');
    }

    public function show($id): View
    {
        $encuesta = Encuesta::find($id);
        return view('encuesta.show', compact('encuesta'));
    }

    public function edit($id): View
    {
        $encuesta = Encuesta::find($id);
        $lineas = LineasProgramaticas::with('dimensiones.subdimensiones')->where('id',$encuesta->id_linea)->get();
        $dimensiones = Dimension::where('id_linea', $encuesta->id_linea)->with('subdimensiones')->get();
        $subdimensiones = $dimensiones->flatMap(function ($dimension) {
            return $dimension->subdimensiones;
        })->values()->all();

        $preguntas = Pregunta::select(
            'preguntas.id',
            'preguntas.id_encuesta',
            'preguntas.id_subdimension',
            'preguntas.texto',
            'preguntas.tipo',
            'preguntas.posicion',
            'preguntas.id_dependencia',
            'preguntas.created_at',
            'preguntas.updated_at',
            'subdimensiones.nombre'
            )
            ->leftjoin('subdimensiones','subdimensiones.id','=','preguntas.id_subdimension')
            ->where('preguntas.id_encuesta', $id)
            ->orderBy('preguntas.posicion','ASC')
            ->get();
        
        foreach($preguntas as $indice => $preg){
            $idp[] = $preg->id;
        }
        if (isset($idp)){
            $alternativas = Alternativa::all()->whereIn('id_pregunta', $idp);
        } else {
            $alternativas = null;
        }

        $cabeceras = CabeceraPregunta::all()->where('id_encuesta', $id);

        foreach ($cabeceras as $item => $valor) {
            $ida[] = $valor->id;
        }

        if (isset($ida)) {
            $cabeceras_alternativas = CabeceraAlternativa::all()->whereIn('id_cabecera', $ida);
        } else {
            $cabeceras_alternativas = null;
        }

        return view('encuesta.edit', compact('encuesta', 'lineas', 'dimensiones', 'subdimensiones', 'preguntas', 'alternativas', 'cabeceras', 'cabeceras_alternativas'));
    }

    public function update(EncuestaRequest $request, $encuesta):RedirectResponse
    {
        //$encuesta->update($request->validated());
        // ID Encuesta
        $lastid = $request->id;

        if ($request->input('action') === 'actualizar_pregunta'){
            
            $encuesta = Encuesta::find($lastid);
            $encuesta->nombre = $request->nombre;
            $encuesta->descripcion = $request->descripcion;
            $encuesta->estado = $request->estado;
            $encuesta->id_linea = $request->id_linea;
            $encuesta->save();
        }
        if ($request->input('action') === 'crear_pregunta') {
            $pregunta = new Pregunta();
            $pregunta->id_encuesta = $lastid;
            $pregunta->id_subdimension = $request->id_subdimension;
            $pregunta->texto = $request->preguntax;
            $pregunta->tipo = $request->tipo;
            $pregunta->posicion = $request->posicion;
            $pregunta->id_dependencia = $request->dependede;
            $pregunta->save();
        }
        if ($request->input('action') === 'crear_alternativa') {
            $alternativa = new Alternativa();

            $alter = $request->alternativa;
            foreach ($alter as $item) {
                if ($item != NULL) {
                    $pos = array_search($item, $alter);
                }
            }
            $alternativa->id_pregunta = $request->idpreg[$pos];
            $alternativa->texto = $request->alternativa[$pos];
            $alternativa->valor = $request->puntaje[$pos];
            $alternativa->id_dependencia = $request->adepende[$pos];
            $alternativa->save();
        }
        if ($request->input('action') === 'crear_cabecera') {
            $cabecera = new CabeceraPregunta();

            if ($request->ctipo != 0) {
                $cabecera->id_encuesta = $lastid;
                $cabecera->tipo = $request->ctipo;
                $cabecera->pregunta = $request->cpregunta;
                $cabecera->estado = $request->estado;
                $cabecera->save();

                if ($request->ctipo == 1) {
                    $lastcab = CabeceraPregunta::latest()->first()->id;

                    foreach ($request->calter as $indice => $calter) {
                        $cabecera_alternativa = new CabeceraAlternativa();
                        $cabecera_alternativa->id_cabecera = $lastcab;
                        $cabecera_alternativa->pregunta = $calter;
                        $cabecera_alternativa->orden = $indice;
                        $cabecera_alternativa->save();
                    }
                } else {
                    return Redirect::route('encuesta.edit', $lastid)
                        ->with('success', 'No se ha registrado cambios');
                }
            }
        }
        return Redirect::route('encuesta.edit', $lastid)
            ->with('success', 'Encuesta updated successfully');
    }

    public function disabled($id): RedirectResponse
    {
        $item = Encuesta::find($id);
        $item->estado = 0;
        $item->save();

        return Redirect::route('encuesta.index')
            ->with('success', 'Encuesta desactivado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        Encuesta::find($id)->delete();

        return Redirect::route('encuesta.index')
            ->with('success', 'Encuesta deleted successfully');
    }

   
}
