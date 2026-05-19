<?php

namespace App\Http\Controllers;

use App\Models\LineasProgramaticas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\LineasProgramaticasRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class LineasProgramaticasController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', LineasProgramaticas::class);

        $lineas = LineasProgramaticas::paginate();
        return view('linea.index', compact('lineas'))
            ->with('i', ($request->input('page', 1) - 1) * $lineas->perPage());
    }

    public function create(): View
    {
        $this->authorize('create', LineasProgramaticas::class);

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
        $linea = LineasProgramaticas::findOrFail($id);
        return view('linea.show', compact('linea'));
    }

    public function edit($id): View
    {
        $linea = LineasProgramaticas::findOrFail($id);
        return view('linea.edit', compact('linea'));
    }

    public function update(LineasProgramaticasRequest $request, LineasProgramaticas $linea)
    {
        $linea->update($request->validated());

        return Redirect::route('linea.index')
            ->with('success', 'Linea Programatica actualizada correctamente');
    }

    public function destroy($id): RedirectResponse
    {
        $linea = LineasProgramaticas::findOrFail($id);
        $linea->delete();
        return Redirect::route('linea.index')
            ->with('success', 'Linea Programatica eliminada correctamente');
    }
}
