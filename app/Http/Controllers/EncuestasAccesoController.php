<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Encuesta;
use App\Models\User;
use App\Models\Dimension;
use App\Models\EncuestaUsuarioDimension;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EncuestasAccesoController extends Controller
{
    /*
    public function index()
    {
        $accesos = EncuestaUsuarioDimension::with(['usuario', 'encuesta', 'dimension'])
            ->orderBy('id_usuario')
            ->orderBy('id_encuesta')
            ->orderBy('orden')
            ->get()
            ->groupBy(function ($item) {
                return $item->id_usuario . '-' . $item->id_encuesta;
            });
        return view('encuestas_accesos.index', compact('accesos'));
    }
    */

    public function index(Request $request)
    {
        $search = $request->get('search');

        // 1. Obtener combinaciones únicas usuario-encuesta (PAGINADAS)
        $accesosBase = EncuestaUsuarioDimension::select(
                'id_usuario',
                'id_encuesta',
                DB::raw('COUNT(*) as total_dimensiones'),
                DB::raw('MIN(id) as id_representativo')
            )
            ->when($search, function ($query, $search) {
                $query->whereHas('usuario', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('ap_paterno', 'like', "%{$search}%")
                    ->orWhere('ap_materno', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                })
                ->orWhereHas('encuesta', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%");
                });
            })
            ->groupBy('id_usuario', 'id_encuesta')
            ->orderBy('id_usuario')
            ->paginate(20);

        // 2. Cargar relaciones completas SOLO de la página actual
        $accesos = collect();

        foreach ($accesosBase as $row) {
            $grupo = EncuestaUsuarioDimension::with(['usuario', 'encuesta', 'dimension'])
                ->where('id_usuario', $row->id_usuario)
                ->where('id_encuesta', $row->id_encuesta)
                ->orderBy('orden')
                ->get();

            $accesos->push($grupo);
        }

        return view('encuestas_accesos.index', compact('accesos', 'accesosBase', 'search'));
    }

    public function create()
    {
        $encuestas   = Encuesta::where('estado', 1)->orderBy('id')->get();
        $usuarios    = User::orderBy('ap_paterno')->orderBy('ap_materno')->orderBy('nombre')->get();
        $dimensiones = Dimension::orderBy('id')->orderBy('id_linea')->get();
        return view('encuestas_accesos.create', compact('encuestas', 'usuarios', 'dimensiones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_encuesta'  => 'required|exists:encuestas,id',
            'id_usuario'   => 'required|exists:users,id',
            'dimensiones'  => 'required|array|min:1',
            'dimensiones.*'=> 'exists:dimensiones,id',
        ]);

        $dimensionesValidas = Dimension::where('id_linea', function ($q) use ($request) {
            $q->select('id_linea')
            ->from('encuestas')
            ->where('id', $request->id_encuesta)
            ->limit(1);
        })->pluck('id')->toArray();

        foreach ($request->dimensiones as $dim) {
            if (!in_array($dim, $dimensionesValidas)) {
                return back()->with('error', 'Una o más dimensiones seleccionadas no pertenecen a esta encuesta.');
            }
        }

        DB::beginTransaction();

        try {
            EncuestaUsuarioDimension::where('id_usuario', $request->id_usuario)
                ->where('id_encuesta', $request->id_encuesta)
                ->delete();
            $orden = 1;
            foreach ($request->dimensiones as $dimId) {
                EncuestaUsuarioDimension::create([
                    'id_usuario'   => $request->id_usuario,
                    'id_encuesta'  => $request->id_encuesta,
                    'id_dimension' => $dimId,
                    'orden'        => $orden++,
                    'estado'       => 1,
                ]);
            }
            DB::commit();
            return redirect()
                ->route('encuestas.accesos.index')
                ->with('success', 'Accesos asignados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Ocurrió un problema al guardar los accesos: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $registro = EncuestaUsuarioDimension::findOrFail($id);

        $encuestaId = $registro->id_encuesta;
        $usuarioId  = $registro->id_usuario;

        $encuestas   = Encuesta::where('estado', 1)->orderBy('id')->get();
        $usuarios    = User::orderBy('ap_paterno')
                            ->orderBy('ap_materno')
                            ->orderBy('nombre')
                            ->get();
        $dimensiones = Dimension::where('id_linea', $registro->dimension->id_linea)
                                ->orderBy('id')
                                ->get();

        $dimensionesSeleccionadas = EncuestaUsuarioDimension::where('id_usuario', $usuarioId)
            ->where('id_encuesta', $encuestaId)
            ->pluck('id_dimension')
            ->toArray();

        return view('encuestas_accesos.edit', compact(
            'registro',
            'encuestas',
            'usuarios',
            'dimensiones',
            'dimensionesSeleccionadas'
        ));
    }

    public function update(Request $request, $id)
    {
        $registro = EncuestaUsuarioDimension::findOrFail($id);

        $request->validate([
            'id_encuesta'  => 'required|exists:encuestas,id',
            'id_usuario'   => 'required|exists:users,id',
            'dimensiones'  => 'required|array|min:1',
            'dimensiones.*'=> 'exists:dimensiones,id',
        ]);

        $dimensionesValidas = Dimension::where('id_linea', function ($q) use ($request) {
            $q->select('id_linea')
            ->from('encuestas')
            ->where('id', $request->id_encuesta)
            ->limit(1);
        })->pluck('id')->toArray();

        foreach ($request->dimensiones as $dim) {
            if (!in_array($dim, $dimensionesValidas)) {
                return back()->with('error', 'Una o más dimensiones seleccionadas no pertenecen a esta encuesta.');
            }
        }

        DB::beginTransaction();

        try {
            EncuestaUsuarioDimension::where('id_usuario', $request->id_usuario)
                ->where('id_encuesta', $request->id_encuesta)
                ->delete();
            $orden = 1;
            foreach ($request->dimensiones as $dimId) {
                EncuestaUsuarioDimension::create([
                    'id_usuario'   => $request->id_usuario,
                    'id_encuesta'  => $request->id_encuesta,
                    'id_dimension' => $dimId,
                    'orden'        => $orden++,
                    'estado'       => 1,
                ]);
            }

            DB::commit();

            return redirect()
                ->route('encuestas.accesos.index')
                ->with('success', 'Accesos actualizados correctamente.');

        } catch (\Exception $e) {

            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Ocurrió un error al actualizar: ' . $e->getMessage());
        }
    }

    public function matriz()
    {
        $usuarios = User::orderBy('ap_paterno')->orderBy('ap_materno')->orderBy('nombre')->get();
        $encuestas = Encuesta::orderBy('nombre')->get();
        $dimensiones = Dimension::orderBy('id')->get();
        $accesos = EncuestaUsuarioDimension::all();

        return view('encuestas_accesos.matriz', compact('usuarios', 'encuestas', 'dimensiones', 'accesos'));
    }

    public function destroy($id)
    {
        $registro = EncuestaUsuarioDimension::findOrFail($id);

        EncuestaUsuarioDimension::where('id_usuario', $registro->id_usuario)
            ->where('id_encuesta', $registro->id_encuesta)
            ->delete();

        return redirect()
            ->route('encuestas.accesos.index')
            ->with('success', 'Acceso eliminado correctamente.');
    }
}
