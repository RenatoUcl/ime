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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $dimensiones = Dimension::paginate();
        $lineas = LineasProgramaticas::all();

        return view('dimension.index', compact('dimensiones','lineas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $dimension = new Dimension();

        return view('dimension.create', compact('dimension'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DimensionRequest $request): RedirectResponse
    {
        Dimension::create($request->validated());

        return Redirect::route('dimension.index')
            ->with('success', 'Dimensión creada satisfactoriamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $dimension = Dimension::find($id);

        return view('dimension.show', compact('dimension'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $dimension = Dimension::find($id);
        return view('dimension.edit', compact('dimension'));
    }

    public function update(Request $request, Dimension $dimension)
    {
        //$dimension->update($request->validated());
        $dimension = Dimension::find($request->id);
        $dimension->nombre = $request->nombre;
        $dimension->descripcion = $request->descripcion;
        $dimension->save();

        return Redirect::route('dimension.index')
            ->with('success', 'Dimensión actualizada satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        Dimension::find($id)->delete();

        return Redirect::route('dimension.index')
            ->with('success', 'Dimensión eliminada satisfactoriamente');
    }
}
