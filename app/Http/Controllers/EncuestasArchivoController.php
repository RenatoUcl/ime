<?php

namespace App\Http\Controllers;

use App\Models\EncuestasArchivo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EncuestasArchivoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EncuestasArchivoController extends Controller
{
    public function index(Request $request): View
    {
        $encuestasArchivos = EncuestasArchivo::with('encuesta')->paginate();

        return view('encuestas-archivo.index', compact('encuestasArchivos'))
            ->with('i', ($request->input('page', 1) - 1) * $encuestasArchivos->perPage());
    }

    public function create(): View
    {
        $encuestasArchivo = new EncuestasArchivo();

        return view('encuestas-archivo.create', compact('encuestasArchivo'));
    }

    public function store(EncuestasArchivoRequest $request): RedirectResponse
    {
        EncuestasArchivo::create($request->validated());

        return Redirect::route('encuestas-archivos.index')
            ->with('success', 'EncuestasArchivo creado satisfactoriamente.');
    }

    public function show($id): View
    {
        $encuestasArchivo = EncuestasArchivo::findOrFail($id);
        return view('encuestas-archivo.show', compact('encuestasArchivo'));
    }

    public function edit($id): View
    {
        $encuestasArchivo = EncuestasArchivo::findOrFail($id);
        return view('encuestas-archivo.edit', compact('encuestasArchivo'));
    }

    public function update(EncuestasArchivoRequest $request, EncuestasArchivo $encuestasArchivo): RedirectResponse
    {
        $encuestasArchivo->update($request->validated());
        return Redirect::route('encuestas-archivos.index')
            ->with('success', 'EncuestasArchivo actualizado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $encuestasArchivo = EncuestasArchivo::findOrFail($id);
        $encuestasArchivo->delete();
        return Redirect::route('encuestas-archivos.index')
            ->with('success', 'EncuestasArchivo eliminado satisfactoriamente');
    }
}
