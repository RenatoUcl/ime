<?php

namespace App\Http\Controllers;

use App\Models\Dimension;
use App\Models\LineasProgramaticas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\DimensionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DimensionController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Dimension::class);

        $dimensiones = Dimension::with(['linea', 'subdimensiones'])->paginate();
        $lineas = LineasProgramaticas::all();

        return view('dimension.index', compact('dimensiones','lineas'));
    }

    public function create(): View
    {
        $this->authorize('create', Dimension::class);

        $dimension = new Dimension();
        return view('dimension.create', compact('dimension'));
    }

    public function store(DimensionRequest $request): RedirectResponse
    {
        Dimension::create($request->validated());

        return Redirect::route('dimension.index')
            ->with('success', 'Dimensión creada satisfactoriamente.');
    }

    public function show($id): View
    {
        $dimension = Dimension::findOrFail($id);
        return view('dimension.show', compact('dimension'));
    }

    public function edit($id): View
    {
        $dimension = Dimension::findOrFail($id);
        return view('dimension.edit', compact('dimension'));
    }

    public function update(Request $request, Dimension $dimension)
    {
        $dimension->nombre = $request->nombre;
        $dimension->descripcion = $request->descripcion;
        $dimension->save();

        return Redirect::route('dimension.index')
            ->with('success', 'Dimensión actualizada satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $dimension = Dimension::findOrFail($id);
        $dimension->delete();

        return Redirect::route('dimension.index')
            ->with('success', 'Dimensión eliminada satisfactoriamente');
    }
}
