<?php

namespace App\Http\Controllers;

use App\Models\NivelesTerciario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\NivelesTerciarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class NivelesTerciarioController extends Controller
{
    public function index(Request $request): View
    {
        $nivelesTerciarios = NivelesTerciario::paginate();
        return view('niveles-terciario.index', compact('nivelesTerciarios'))
            ->with('i', ($request->input('page', 1) - 1) * $nivelesTerciarios->perPage());
    }

    public function create(): View
    {
        $nivelesTerciario = new NivelesTerciario();
        return view('niveles-terciario.create', compact('nivelesTerciario'));
    }

    public function store(NivelesTerciarioRequest $request): RedirectResponse
    {
        NivelesTerciario::create($request->validated());
        return Redirect::route('niveles-terciario.index')
            ->with('success', 'Nivel terciario creado satisfactoriamente.');
    }

    public function show($id): View
    {
        $nivelesTerciario = NivelesTerciario::findOrFail($id);
        return view('niveles-terciario.show', compact('nivelesTerciario'));
    }

    public function edit($id): View
    {
        $nivelesTerciario = NivelesTerciario::findOrFail($id);
        return view('niveles-terciario.edit', compact('nivelesTerciario'));
    }

    public function update(NivelesTerciarioRequest $request, NivelesTerciario $nivelesTerciario): RedirectResponse
    {
        $nivelesTerciario->update($request->validated());
        return Redirect::route('niveles-terciario.index')
            ->with('success', 'Nivel terciario actualizado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $nivelesTerciario = NivelesTerciario::findOrFail($id);
        $nivelesTerciario->delete();
        return Redirect::route('niveles-terciario.index')
            ->with('success', 'Nivel terciario eliminado satisfactoriamente');
    }
}
