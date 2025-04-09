<?php

namespace App\Http\Controllers;

use App\Models\Departamento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DepartamentoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DepartamentoController extends Controller
{
    public function index(Request $request): View
    {
        $departamentos = Departamento::paginate();
        return view('departamento.index', compact('departamentos'))
            ->with('i', ($request->input('page', 1) - 1) * $departamentos->perPage());
    }

    public function create(): View
    {
        $departamento = new Departamento();
        return view('departamento.create', compact('departamento'));
    }

    public function store(DepartamentoRequest $request): RedirectResponse
    {
        Departamento::create($request->validated());
        return Redirect::route('departamento.index')
            ->with('success', 'Departamento creado satisfactoriamente');
    }

    public function show($id): View
    {
        $departamento = Departamento::find($id);
        return view('departamento.show', compact('departamento'));
    }

    public function edit($id): View
    {
        $departamento = Departamento::find($id);
        return view('departamento.edit', compact('departamento'));
    }

    public function update(DepartamentoRequest $request, Departamento $departamento): RedirectResponse
    {
        $departamento->update($request->validated());
        return Redirect::route('departamento.index')
            ->with('success', 'Departamento actualizado correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        Departamento::find($id)->delete();
        return Redirect::route('departamento.index')
            ->with('success', 'Departamento eliminado correctamente');
    }
}
