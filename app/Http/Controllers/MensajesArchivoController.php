<?php

namespace App\Http\Controllers;

use App\Models\MensajesArchivo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MensajesArchivoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MensajesArchivoController extends Controller
{
    public function index(Request $request): View
    {
        $mensajesArchivos = MensajesArchivo::paginate();
        return view('mensajes-archivo.index', compact('mensajesArchivos'))
            ->with('i', ($request->input('page', 1) - 1) * $mensajesArchivos->perPage());
    }

    public function create(): View
    {
        $mensajesArchivo = new MensajesArchivo();
        return view('mensajes-archivo.create', compact('mensajesArchivo'));
    }

    public function store(MensajesArchivoRequest $request): RedirectResponse
    {
        MensajesArchivo::create($request->validated());
        return Redirect::route('mensajes-archivo.index')
            ->with('success', 'MensajesArchivo creado satisfactoriamente.');
    }

    public function show($id): View
    {
        $mensajesArchivo = MensajesArchivo::findOrFail($id);
        return view('mensajes-archivo.show', compact('mensajesArchivo'));
    }

    public function edit($id): View
    {
        $mensajesArchivo = MensajesArchivo::findOrFail($id);
        return view('mensajes-archivo.edit', compact('mensajesArchivo'));
    }

    public function update(MensajesArchivoRequest $request, MensajesArchivo $mensajesArchivo): RedirectResponse
    {
        $mensajesArchivo->update($request->validated());
        return Redirect::route('mensajes-archivo.index')
            ->with('success', 'MensajesArchivo actualizado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $mensajesArchivo = MensajesArchivo::findOrFail($id);
        $mensajesArchivo->delete();
        return Redirect::route('mensajes-archivo.index')
            ->with('success', 'MensajesArchivo eliminado satisfactoriamente');
    }
}
