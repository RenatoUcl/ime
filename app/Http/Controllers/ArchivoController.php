<?php

namespace App\Http\Controllers;

use App\Models\Archivo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ArchivoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $archivos = Archivo::paginate();

        return view('archivo.index', compact('archivos'))
            ->with('i', ($request->input('page', 1) - 1) * $archivos->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $archivo = new Archivo();

        return view('archivo.create', compact('archivo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArchivoRequest $request): RedirectResponse
    {
        Archivo::create($request->validated());

        return Redirect::route('archivos.index')
            ->with('success', 'Archivo created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $archivo = Archivo::find($id);

        return view('archivo.show', compact('archivo'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $archivo = Archivo::find($id);

        return view('archivo.edit', compact('archivo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArchivoRequest $request, Archivo $archivo): RedirectResponse
    {
        $archivo->update($request->validated());

        return Redirect::route('archivos.index')
            ->with('success', 'Archivo updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Archivo::find($id)->delete();

        return Redirect::route('archivos.index')
            ->with('success', 'Archivo deleted successfully');
    }
}
