<?php

namespace App\Http\Controllers;

use App\Models\LineasProgramaticas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LineasProgramaticasRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class LineasProgramaticasController extends Controller
{
    public function index(Request $request): View
    {
        $lineas = LineasProgramaticas::paginate();
        return view('linea.index', compact('lineas'))
            ->with('i', ($request->input('page', 1) - 1) * $lineas->perPage());
    }

    public function create(): View
    {
        $linea = new LineasProgramaticas();
        return view('linea.create', compact('linea'));
    }

    public function store(LineasProgramaticasRequest $request): RedirectResponse
    {
        LineasProgramaticas::create($request->validated());
        return Redirect::route('linea.index')
            ->with('success', 'Linea Programatica creada satisfactoriamente');
    }

    public function show($id): View
    {
        $linea = LineasProgramaticas::find($id);
        return view('linea.show', compact('linea'));
    }

    public function edit($id): View
    {
        $linea = LineasProgramaticas::find($id);
        return view('linea.edit', compact('linea'));
    }

    public function update(LineasProgramaticasRequest $request, LineasProgramaticas $linea)
    {
        //$linea->update($request->validated());
        $linea = LineasProgramaticas::find($request->id);
        $linea->nombre = $request->nombre;
        $linea->descripcion = $request->descripcion;
        $linea->save();

        return Redirect::route('linea.index')
            ->with('success', 'Linea Programatica actualizada correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        LineasProgramaticas::find($id)->delete();
        return Redirect::route('linea.index')
            ->with('success', 'Linea Programatica eliminada correctamente');
    }
}
