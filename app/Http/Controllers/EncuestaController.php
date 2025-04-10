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

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $encuesta = new Encuesta();
        $lineas = LineasProgramaticas::all();
        $dimensiones = Dimension::all();
        $subdimensiones = Subdimension::all();

        $preguntas = null;
        $alternativas = null;
        $cabeceras = null;
        $cabeceras_alternativas = null;

        return view('encuesta.create', compact('encuesta','lineas','dimensiones','subdimensiones','preguntas','alternativas','cabeceras','cabeceras_alternativas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EncuestaRequest $request): RedirectResponse
    {
        Encuesta::create($request->validated());

        dd($request->toArray());

        // Crear la nueva encuesta
        $encuesta = new Encuesta();
        $encuesta->id_linea = $validated['id_linea'];
        $encuesta->nombre = $validated['nombre'];
        $encuesta->descripcion = $validated['descripcion'] ?? '';
        $encuesta->estado = true;

        $encuesta->save();

        $lastid = Encuesta::latest()->first()->id;

        return Redirect::route('encuesta.edit', $lastid)
            ->with('success', 'Encuesta created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $encuesta = Encuesta::find($id);

        return view('encuesta.show', compact('encuesta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
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

        return view('encuesta.edit', compact('encuesta', 'subdimensiones', 'preguntas', 'alternativas', 'cabeceras', 'cabeceras_alternativas','dimensiones'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EncuestaRequest $request, $encuesta): RedirectResponse
    {
        //$encuesta->update($request->validated());
        // ID Encuesta
        $lastid = $request->id;

        if ($request->input('action') === 'actualizar_pregunta'){
            $encuesta = Encuesta::find($lastid);
            $encuesta->nombre = $request->nombre;
            $encuesta->descripcion = $request->descripcion;
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

    // Inicia la encuesta uno a uno

    public function iniciar($id)
    {
        $encuesta = Encuesta::findOrFail($id);
        $pregunta = $encuesta->preguntas()->orderBy('posicion')->first();
    
        return view('encuesta.responder', compact('encuesta', 'pregunta'));
    }
    
    public function responder(Request $request)
    {
        // Guardar la respuesta actual
        Respuesta::create([
            'id_pregunta' => $request->id_pregunta,
            'id_alternativa' => $request->id_alternativa,
            'texto' => $request->texto,
            'valor' => $request->valor ?? 0,
            'nivel' => $request->nivel ?? 0,
        ]);
    
        // Buscar la siguiente pregunta
        $preguntaActual = Pregunta::find($request->id_pregunta);
        $preguntaSiguiente = Pregunta::where('id_encuesta', $preguntaActual->id_encuesta)
            ->where('posicion', '>', $preguntaActual->posicion)
            ->orderBy('posicion')
            ->first();
    
        if ($preguntaSiguiente) {
            return response()->json([
                'success' => true,
                'html' => view('encuesta.pregunta', ['pregunta' => $preguntaSiguiente])->render()
            ]);
        } else {
            return response()->json(['success' => true, 'terminado' => true]);
        }
    }
    
}
