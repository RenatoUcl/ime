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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $encuestasArchivos = EncuestasArchivo::paginate();

        return view('encuestas-archivo.index', compact('encuestasArchivos'))
            ->with('i', ($request->input('page', 1) - 1) * $encuestasArchivos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $encuestasArchivo = new EncuestasArchivo();

        return view('encuestas-archivo.create', compact('encuestasArchivo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EncuestasArchivoRequest $request): RedirectResponse
    {
        EncuestasArchivo::create($request->validated());

        return Redirect::route('encuestas-archivos.index')
            ->with('success', 'EncuestasArchivo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $encuestasArchivo = EncuestasArchivo::find($id);

        return view('encuestas-archivo.show', compact('encuestasArchivo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $encuestasArchivo = EncuestasArchivo::find($id);

        return view('encuestas-archivo.edit', compact('encuestasArchivo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EncuestasArchivoRequest $request, EncuestasArchivo $encuestasArchivo): RedirectResponse
    {
        $encuestasArchivo->update($request->validated());

        return Redirect::route('encuestas-archivos.index')
            ->with('success', 'EncuestasArchivo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EncuestasArchivo::find($id)->delete();

        return Redirect::route('encuestas-archivos.index')
            ->with('success', 'EncuestasArchivo deleted successfully');
    }
}
