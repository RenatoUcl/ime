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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $mensajesArchivos = MensajesArchivo::paginate();

        return view('mensajes-archivo.index', compact('mensajesArchivos'))
            ->with('i', ($request->input('page', 1) - 1) * $mensajesArchivos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $mensajesArchivo = new MensajesArchivo();

        return view('mensajes-archivo.create', compact('mensajesArchivo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MensajesArchivoRequest $request): RedirectResponse
    {
        MensajesArchivo::create($request->validated());

        return Redirect::route('mensajes-archivos.index')
            ->with('success', 'MensajesArchivo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $mensajesArchivo = MensajesArchivo::find($id);

        return view('mensajes-archivo.show', compact('mensajesArchivo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $mensajesArchivo = MensajesArchivo::find($id);

        return view('mensajes-archivo.edit', compact('mensajesArchivo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MensajesArchivoRequest $request, MensajesArchivo $mensajesArchivo): RedirectResponse
    {
        $mensajesArchivo->update($request->validated());

        return Redirect::route('mensajes-archivos.index')
            ->with('success', 'MensajesArchivo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        MensajesArchivo::find($id)->delete();

        return Redirect::route('mensajes-archivos.index')
            ->with('success', 'MensajesArchivo deleted successfully');
    }
}
