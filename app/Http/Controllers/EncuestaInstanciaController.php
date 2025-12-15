<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\EncuestaInstancia;
use Illuminate\Http\Request;

class EncuestaInstanciaController extends Controller
{
    /**
     * Lista todas las instancias de la encuesta
     */
    public function index($encuestaId)
    {
        $encuesta = Encuesta::findOrFail($encuestaId);

        $instancias = EncuestaInstancia::where('id_encuesta', $encuestaId)
            ->orderBy('id', 'desc')
            ->get();

        return view('encuestas.instancias.index', compact('encuesta', 'instancias'));
    }

    /**
     * Formulario crear instancia nueva
     */
    public function create($encuestaId)
    {
        $encuesta = Encuesta::findOrFail($encuestaId);
        return view('encuestas.instancias.create', compact('encuesta'));
    }

    /**
     * Guardar instancia nueva
     */
    public function store(Request $request, $encuestaId)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin'    => 'required|date|after_or_equal:fecha_inicio',
        ]);

        EncuestaInstancia::create([
            'id_encuesta' => $encuestaId,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin'    => $request->fecha_fin,
            'activa'       => 1,
        ]);

        return redirect()
            ->route('encuestas.instancias.index', $encuestaId)
            ->with('success', 'Instancia creada correctamente');
    }

    /**
     * Cerrar manualmente una instancia
     */
    public function close($encuestaId, $instanciaId)
    {
        $instancia = EncuestaInstancia::where('id_encuesta', $encuestaId)
            ->where('id', $instanciaId)
            ->firstOrFail();

        $instancia->update([
            'activa'     => 0,
            'fecha_fin'  => now(),
        ]);

        return redirect()
            ->route('encuestas.instancias.index', $encuestaId)
            ->with('success', 'La instancia fue cerrada correctamente');
    }
}
