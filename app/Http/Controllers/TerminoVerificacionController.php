<?php

namespace App\Http\Controllers;

use App\Models\TerminoVerificacion;
use App\Models\Subdimension;
use App\Models\SubdimensionTermino;
use Illuminate\Http\Request;

class TerminoVerificacionController extends Controller
{

    public function index()
    {
        $terminos =
        TerminoVerificacion::with([
            'subdimensiones'
        ])
        ->orderBy('termino')
        ->get();

        return view(
            'terminos-verificacion.index',
            compact('terminos')
        );
    }

    public function create()
    {
        $subdimensiones =
        Subdimension::with('dimension')
        ->where('estado',1)
        ->get();

        return view(
            'terminos-verificacion.create',
            compact('subdimensiones')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'termino'=>'required|string|max:255',
            'subdimensiones'=>'required|array'
        ]);

        $termino =
        TerminoVerificacion::create([
            'termino'=>$request->termino,
            'estado'=>1
        ]);

        foreach(
            $request->subdimensiones
            as $idSubdimension
        )
        {
            SubdimensionTermino::create([
                'id_subdimension'=>$idSubdimension,
                'id_termino'=>$termino->id,
                'peso'=>
                $request->peso ?? 1,
                'obligatorio'=>
                $request->has('obligatorio')
            ]);
        }

        return redirect()
        ->route(
            'terminos-verificacion.index'
        )
        ->with(
            'success',
            'Término creado correctamente'
        );

    }

    public function edit($id)
    {
        $termino =
        TerminoVerificacion::with(
            'subdimensiones'
        )
        ->findOrFail($id);

        $subdimensiones =
        Subdimension::with('dimension')
        ->where('estado',1)
        ->get();

        return view(
            'terminos-verificacion.edit',
            compact(
                'termino',
                'subdimensiones'
            )
        );

    }

    public function update(Request $request, $id)
    {
        $termino =
        TerminoVerificacion::findOrFail($id);

        $request->validate([
            'termino'=>'required'
        ]);

        $termino->update([
            'termino'=>$request->termino
        ]);

        // borrar relaciones anteriores
        SubdimensionTermino::where(
            'id_termino',
            $termino->id
        )
        ->delete();

        foreach(
            $request->subdimensiones
            as $idSubdimension
        )
        {
            SubdimensionTermino::create([
                'id_subdimension'=>$idSubdimension,
                'id_termino'=>$termino->id,
                'peso'=>$request->peso ?? 1,
                'obligatorio'=>
                $request->has('obligatorio')
            ]);
        }

        return redirect()
        ->route(
            'terminos-verificacion.index'
        );

    }

    public function destroy($id)
    {
        $termino =
        TerminoVerificacion::findOrFail($id);

        $termino->update([
            'estado'=>0
        ]);

        return back();
    }

}
