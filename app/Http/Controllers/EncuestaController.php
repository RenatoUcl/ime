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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class EncuestaController extends Controller
{

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Encuesta::class);

        $encuestas = Encuesta::with('linea')->paginate();

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
        $encuesta = Encuesta::create($request->validated());

        return Redirect::route('encuesta.edit', $encuesta->id)
            ->with('success', 'Encuesta creada satisfactoriamente.');
    }

    public function show($id): View
    {
        $encuesta = Encuesta::findOrFail($id);
        return view('encuesta.show', compact('encuesta'));
    }

    public function edit($id): View
    {
        $encuesta = Encuesta::findOrFail($id);
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
            ->orderBy('preguntas.id','ASC')
            ->orderBy('preguntas.posicion','ASC')
            ->get();

        $idp = $preguntas->pluck('id')->toArray();

        if (!empty($idp)) {
            $alternativas = Alternativa::whereIn('id_pregunta', $idp)->get();
        } else {
            $alternativas = collect();
        }

        $cabeceras = CabeceraPregunta::where('id_encuesta', $id)->get();

        $ida = $cabeceras->pluck('id')->toArray();

        if (!empty($ida)) {
            $cabeceras_alternativas = CabeceraAlternativa::whereIn('id_cabecera', $ida)->get();
        } else {
            $cabeceras_alternativas = collect();
        }

        return view('encuesta.edit', compact('encuesta', 'lineas', 'dimensiones', 'subdimensiones', 'preguntas', 'alternativas', 'cabeceras', 'cabeceras_alternativas'));
    }

    public function update(EncuestaRequest $request, $id): RedirectResponse
    {
        $encuesta = Encuesta::findOrFail($id);

        if ($request->input('action') === 'actualizar_pregunta'){
            $encuesta->nombre = $request->nombre;
            $encuesta->descripcion = $request->descripcion;
            $encuesta->estado = $request->estado;
            $encuesta->id_linea = $request->id_linea;
            $encuesta->save();
        }
        if ($request->input('action') === 'crear_pregunta') {
            $pregunta = new Pregunta();
            $pregunta->id_encuesta = $encuesta->id;
            $pregunta->id_subdimension = $request->id_subdimension;
            $pregunta->texto = $request->preguntax;
            $pregunta->tipo = $request->tipo;
            $pregunta->posicion = $request->posicion;
            $pregunta->id_dependencia = $request->dependede;
            $pregunta->save();
        }
        if ($request->input('action') === 'crear_alternativa') {
            $alterTextos = $request->alternativa ?? [];
            $idPregs = $request->idpreg ?? [];
            $puntajes = $request->puntaje ?? [];
            $adependes = $request->adepende ?? [];

            foreach ($alterTextos as $index => $texto) {
                if ($texto !== null && $texto !== '') {
                    $alternativa = new Alternativa();
                    $alternativa->id_pregunta = $idPregs[$index] ?? 0;
                    $alternativa->texto = $texto;
                    $alternativa->valor = $puntajes[$index] ?? 0;
                    $alternativa->id_dependencia = $adependes[$index] ?? 0;
                    $alternativa->save();
                }
            }
        }

        if ($request->input('action') === 'editar_alternativa') {

            if ($request->has('edit_id_alternativa')) {

                foreach ($request->edit_id_alternativa as $idAlt) {

                    Alternativa::where('id', $idAlt)->update([
                        'texto'          => $request->edit_alternativa[$idAlt] ?? '',
                        'valor'          => $request->edit_puntaje[$idAlt] ?? 0,
                        'id_dependencia' => $request->edit_adepende[$idAlt] ?? 0,
                    ]);
                }
            }

            return Redirect::route('encuesta.edit', $encuesta->id)
                ->with('success', 'Alternativa actualizada correctamente.');
        }

        if ($request->input('action') === 'crear_cabecera') {

            if ($request->ctipo != 0) {
                $cabecera = new CabeceraPregunta();
                $cabecera->id_encuesta = $encuesta->id;
                $cabecera->tipo = $request->ctipo;
                $cabecera->pregunta = $request->cpregunta;
                $cabecera->estado = $request->estado;
                $cabecera->save();

                if ($request->ctipo == 1 && $request->has('calter')) {
                    foreach ($request->calter as $indice => $calter) {
                        $cabecera_alternativa = new CabeceraAlternativa();
                        $cabecera_alternativa->id_cabecera = $cabecera->id;
                        $cabecera_alternativa->pregunta = $calter;
                        $cabecera_alternativa->orden = $indice;
                        $cabecera_alternativa->save();
                    }
                } else {
                    return Redirect::route('encuesta.edit', $encuesta->id)
                        ->with('success', 'No se ha registrado cambios');
                }
            }
        }
        return Redirect::route('encuesta.edit', $encuesta->id)
            ->with('success', 'Encuesta actualizada satisfactoriamente');
    }

    public function disabled($id): RedirectResponse
    {
        $item = Encuesta::findOrFail($id);
        $item->estado = 0;
        $item->save();

        return Redirect::route('encuesta.index')
            ->with('success', 'Encuesta desactivado satisfactoriamente');
    }

    public function clonarEncuesta($idEncuestaOriginal)
    {
        DB::beginTransaction();

        try {
            $encuesta = Encuesta::findOrFail($idEncuestaOriginal);

            $nuevaEncuesta = Encuesta::create([
                'id_linea'   => $encuesta->id_linea,
                'nombre'     => $encuesta->nombre . ' (Copia)',
                'descripcion'=> $encuesta->descripcion,
                'estado'     => 0,
            ]);

            $nuevaEncuestaId = $nuevaEncuesta->id;

            $preguntas = Pregunta::where('id_encuesta', $idEncuestaOriginal)->get();

            $mapPreguntas = [];

            foreach ($preguntas as $pregunta) {
                $nuevaPregunta = Pregunta::create([
                    'id_encuesta'     => $nuevaEncuestaId,
                    'id_subdimension' => $pregunta->id_subdimension,
                    'tipo'            => $pregunta->tipo,
                    'texto'           => $pregunta->texto,
                    'posicion'        => $pregunta->posicion,
                    'id_dependencia'  => null,
                ]);

                $mapPreguntas[$pregunta->id] = $nuevaPregunta->id;
            }

            foreach ($preguntas as $pregunta) {
                if ($pregunta->id_dependencia && isset($mapPreguntas[$pregunta->id_dependencia])) {
                    Pregunta::where('id', $mapPreguntas[$pregunta->id])
                        ->update([
                            'id_dependencia' => $mapPreguntas[$pregunta->id_dependencia]
                        ]);
                }
            }

            $mapAlternativas = [];

            foreach ($mapPreguntas as $oldPreguntaId => $newPreguntaId) {
                $alternativas = Alternativa::where('id_pregunta', $oldPreguntaId)->get();

                foreach ($alternativas as $alt) {
                    $newAlt = Alternativa::create([
                        'id_pregunta'    => $newPreguntaId,
                        'id_dependencia' => 0,
                        'texto'          => $alt->texto,
                        'valor'          => $alt->valor,
                    ]);

                    $mapAlternativas[$alt->id] = $newAlt->id;
                }
            }

            foreach ($mapAlternativas as $oldAltId => $newAltId) {
                $altOriginal = Alternativa::find($oldAltId);

                if ($altOriginal && (int)$altOriginal->id_dependencia > 0) {
                    Alternativa::where('id', $newAltId)
                        ->update([
                            'id_dependencia' => $mapAlternativas[$altOriginal->id_dependencia] ?? 0
                        ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('encuesta.index')
                ->with('success', 'Encuesta clonada correctamente. Debe crear un Período de aplicación.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'error'   => 'Error al clonar la encuesta',
                'detalle' => $e->getMessage()
            ], 500);
        }
    } 

    public function destroy($id): RedirectResponse
    {
        $encuesta = Encuesta::findOrFail($id);
        $encuesta->delete();

        return Redirect::route('encuesta.index')
            ->with('success', 'Encuesta eliminada satisfactoriamente');
    }

}
