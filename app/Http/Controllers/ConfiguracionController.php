<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\ConfiguracionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ConfiguracionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $configuracions = Configuracion::paginate();

        return view('configuracion.index', compact('configuracions'))
            ->with('i', ($request->input('page', 1) - 1) * $configuracions->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $configuracion = new Configuracion();

        return view('configuracion.create', compact('configuracion'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ConfiguracionRequest $request): RedirectResponse
    {
        Configuracion::create($request->validated());

        return Redirect::route('configuracions.index')
            ->with('success', 'Configuracion created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $configuracion = Configuracion::find($id);

        return view('configuracion.show', compact('configuracion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $configuracion = Configuracion::find($id);

        return view('configuracion.edit', compact('configuracion'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ConfiguracionRequest $request, Configuracion $configuracion): RedirectResponse
    {
        $configuracion->update($request->validated());

        return Redirect::route('configuracions.index')
            ->with('success', 'Configuracion updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Configuracion::find($id)->delete();

        return Redirect::route('configuracions.index')
            ->with('success', 'Configuracion deleted successfully');
    }
}
