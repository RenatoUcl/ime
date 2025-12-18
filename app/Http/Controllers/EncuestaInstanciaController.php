<?php

namespace App\Http\Controllers;

use App\Models\Encuesta;
use App\Models\EncuestaInstancia;
use Illuminate\Http\Request;

class EncuestaInstanciaController extends Controller
{
    public function index($encuestaId)
    {
        $encuesta = Encuesta::findOrFail($encuestaId);

        $periodos = EncuestaInstancia::where('id_encuesta', $encuestaId)
            ->orderByDesc('fecha_desde')
            ->get();

        return view('encuestas_periodos.index', compact('encuesta', 'periodos'));
    }

    public function create($encuestaId)
    {
        $encuesta = Encuesta::findOrFail($encuestaId);
        return view('encuestas_periodos.create', compact('encuesta'));
    }

    public function store(Request $request, $encuestaId)
    {
        $request->validate([
            'nombre_periodo' => 'required|string|max:255',
            'fecha_desde'    => 'required|date',
            'fecha_hasta'    => 'required|date|after_or_equal:fecha_desde',
            'estado'         => 'nullable|boolean',
        ]);

        // Opción A: Bloquear solapamientos (solo considerar períodos activos)
        $haySolapamiento = EncuestaInstancia::where('id_encuesta', $encuestaId)
            ->where('estado', 1)
            ->where(function ($q) use ($request) {
                // overlap: (start <= existing_end) AND (end >= existing_start)
                $q->whereDate('fecha_desde', '<=', $request->fecha_hasta)
                  ->whereDate('fecha_hasta', '>=', $request->fecha_desde);
            })
            ->exists();

        if ($haySolapamiento) {
            return back()
                ->withInput()
                ->withErrors(['fecha_desde' => 'Ya existe un período activo que se cruza con estas fechas. Ajuste el rango o cierre el otro período.']);
        }

        EncuestaInstancia::create([
            'id_encuesta'    => $encuestaId,
            'nombre_periodo' => $request->nombre_periodo,
            'fecha_desde'    => $request->fecha_desde,
            'fecha_hasta'    => $request->fecha_hasta,
            'estado'         => $request->has('estado') ? (int)$request->estado : 1,
        ]);

        return redirect()
            ->route('encuestas.periodos.index', $encuestaId)
            ->with('success', 'Período de aplicación creado correctamente.');
    }

    public function edit($id)
    {
        $periodo = EncuestaInstancia::findOrFail($id);
        $encuesta = $periodo->encuesta;

        return view('encuestas_periodos.edit', compact('periodo', 'encuesta'));
    }

    public function update(Request $request, $id)
    {
        $periodo = EncuestaInstancia::findOrFail($id);

        $request->validate([
            'nombre_periodo' => 'required|string|max:255',
            'fecha_desde'    => 'required|date',
            'fecha_hasta'    => 'required|date|after_or_equal:fecha_desde',
            'estado'         => 'required|boolean',
        ]);

        // Opción A: Bloquear solapamientos (solo considerar períodos activos, excluyendo este)
        if ((int)$request->estado === 1) {
            $haySolapamiento = EncuestaInstancia::where('id_encuesta', $periodo->id_encuesta)
                ->where('estado', 1)
                ->where('id', '!=', $periodo->id)
                ->where(function ($q) use ($request) {
                    $q->whereDate('fecha_desde', '<=', $request->fecha_hasta)
                      ->whereDate('fecha_hasta', '>=', $request->fecha_desde);
                })
                ->exists();

            if ($haySolapamiento) {
                return back()
                    ->withInput()
                    ->withErrors(['fecha_desde' => 'Ya existe un período activo que se cruza con estas fechas. Ajuste el rango o cierre el otro período.']);
            }
        }

        $periodo->update([
            'nombre_periodo' => $request->nombre_periodo,
            'fecha_desde'    => $request->fecha_desde,
            'fecha_hasta'    => $request->fecha_hasta,
            'estado'         => (int)$request->estado,
        ]);

        return redirect()
            ->route('encuestas.periodos.index', $periodo->id_encuesta)
            ->with('success', 'Período de aplicación actualizado correctamente.');
    }

    public function destroy($id)
    {
        $periodo = EncuestaInstancia::findOrFail($id);
        $encuestaId = $periodo->id_encuesta;

        $periodo->delete();

        return redirect()
            ->route('encuestas.periodos.index', $encuestaId)
            ->with('success', 'Período eliminado.');
    }
}