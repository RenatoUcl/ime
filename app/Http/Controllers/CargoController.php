<?php

namespace App\Http\Controllers;

use App\Models\Cargos;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CargoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CargoController extends Controller
{
    public function index(Request $request): View
    {
        $cargos = Cargos::paginate();
        return view('cargo.index', compact('cargos'))
            ->with('i', ($request->input('page', 1) - 1) * $cargos->perPage());
    }

    public function create(): View
    {
        $cargo = new Cargos();
        return view('cargo.create', compact('cargo'));
    }

    public function store(CargoRequest $request): RedirectResponse
    {
        Cargos::create($request->validated());
        return Redirect::route('cargo.index')
            ->with('success', 'Cargo creado satisfactoriamente');
    }

    public function show($id): View
    {
        $cargo = Cargos::find($id);
        return view('cargo.show', compact('cargo'));
    }

    public function edit($id): View
    {
        $cargo = Cargo::find($id);
        return view('cargo.edit', compact('cargo'));
    }

    public function update(CargoRequest $request, Cargo $cargo): RedirectResponse
    {
        $cargo->update($request->validated());
        return Redirect::route('cargo.index')
            ->with('success', 'Cargo actualizado correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        Cargos::find($id)->delete();
        return Redirect::route('cargo.index')
            ->with('success', 'Cargo eliminado correctamente');
    }
}
