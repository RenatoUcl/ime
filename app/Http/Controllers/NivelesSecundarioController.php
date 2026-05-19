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
    public function index(Request $request): View
    {
        $nivelesSecundarios = NivelesSecundario::paginate();
        return view('niveles-secundario.index', compact('nivelesSecundarios'))
            ->with('i', ($request->input('page', 1) - 1) * $nivelesSecundarios->perPage());
    }

    public function create(): View
    {
        $nivelesSecundario = new NivelesSecundario();
        return view('niveles-secundario.create', compact('nivelesSecundario'));
    }

    public function store(NivelesSecundarioRequest $request): RedirectResponse
    {
        NivelesSecundario::create($request->validated());
        return Redirect::route('niveles-secundario.index')
            ->with('success', 'Nivel secundario creado satisfactoriamente.');
    }

    public function show($id): View
    {
        $nivelesSecundario = NivelesSecundario::findOrFail($id);
        return view('niveles-secundario.show', compact('nivelesSecundario'));
    }

    public function edit($id): View
    {
        $nivelesSecundario = NivelesSecundario::findOrFail($id);
        return view('niveles-secundario.edit', compact('nivelesSecundario'));
    }

    public function update(NivelesSecundarioRequest $request, NivelesSecundario $nivelesSecundario): RedirectResponse
    {
        $nivelesSecundario->update($request->validated());
        return Redirect::route('niveles-secundario.index')
            ->with('success', 'Nivel secundario actualizado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $nivelesSecundario = NivelesSecundario::findOrFail($id);
        $nivelesSecundario->delete();
        return Redirect::route('niveles-secundario.index')
            ->with('success', 'Nivel secundario eliminado satisfactoriamente');
    }
}
