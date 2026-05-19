<?php

namespace App\Http\Controllers;

use App\Models\NivelesPrimario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\NivelesPrimarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class NivelesPrimarioController extends Controller
{
    public function index(Request $request): View
    {
        $nivelesPrimarios = NivelesPrimario::paginate();
        return view('niveles-primario.index', compact('nivelesPrimarios'))
            ->with('i', ($request->input('page', 1) - 1) * $nivelesPrimarios->perPage());
    }

    public function create(): View
    {
        $nivelesPrimario = new NivelesPrimario();
        return view('niveles-primario.create', compact('nivelesPrimario'));
    }

    public function store(NivelesPrimarioRequest $request): RedirectResponse
    {
        NivelesPrimario::create($request->validated());
        return Redirect::route('niveles-primario.index')
            ->with('success', 'Nivel primario creado satisfactoriamente.');
    }

    public function show($id): View
    {
        $nivelesPrimario = NivelesPrimario::findOrFail($id);
        return view('niveles-primario.show', compact('nivelesPrimario'));
    }

    public function edit($id): View
    {
        $nivelesPrimario = NivelesPrimario::findOrFail($id);
        return view('niveles-primario.edit', compact('nivelesPrimario'));
    }

    public function update(NivelesPrimarioRequest $request, NivelesPrimario $nivelesPrimario): RedirectResponse
    {
        $nivelesPrimario->update($request->validated());
        return Redirect::route('niveles-primario.index')
            ->with('success', 'Nivel primario actualizado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $nivelesPrimario = NivelesPrimario::findOrFail($id);
        $nivelesPrimario->delete();
        return Redirect::route('niveles-primario.index')
            ->with('success', 'Nivel primario eliminado satisfactoriamente');
    }
}
