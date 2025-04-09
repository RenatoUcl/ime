<?php

namespace App\Http\Controllers;

use App\Models\NivelesSecundario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\NivelesSecundarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class NivelesSecundarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $nivelesSecundarios = NivelesSecundario::paginate();

        return view('niveles-secundario.index', compact('nivelesSecundarios'))
            ->with('i', ($request->input('page', 1) - 1) * $nivelesSecundarios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $nivelesSecundario = new NivelesSecundario();

        return view('niveles-secundario.create', compact('nivelesSecundario'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NivelesSecundarioRequest $request): RedirectResponse
    {
        NivelesSecundario::create($request->validated());

        return Redirect::route('niveles-secundarios.index')
            ->with('success', 'NivelesSecundario created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $nivelesSecundario = NivelesSecundario::find($id);

        return view('niveles-secundario.show', compact('nivelesSecundario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $nivelesSecundario = NivelesSecundario::find($id);

        return view('niveles-secundario.edit', compact('nivelesSecundario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NivelesSecundarioRequest $request, NivelesSecundario $nivelesSecundario): RedirectResponse
    {
        $nivelesSecundario->update($request->validated());

        return Redirect::route('niveles-secundarios.index')
            ->with('success', 'NivelesSecundario updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        NivelesSecundario::find($id)->delete();

        return Redirect::route('niveles-secundarios.index')
            ->with('success', 'NivelesSecundario deleted successfully');
    }
}
