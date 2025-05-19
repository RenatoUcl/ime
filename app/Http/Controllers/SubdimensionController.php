<?php

namespace App\Http\Controllers;

use App\Models\Subdimension;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\SubdimensionRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class SubdimensionController extends Controller
{
    public function index(Request $request): View
    {
        //$subdimensiones = Subdimension::paginate();
        $subdimensiones = Subdimension::select(
            'subdimensiones.id',
            'subdimensiones.id_dimension',
            'dimensiones.nombre as dimension',
            'subdimensiones.nombre',
            'subdimensiones.descripcion',
            'subdimensiones.posicion',
            'subdimensiones.estado',
            'subdimensiones.created_at',
            'subdimensiones.updated_at'
            )
            ->leftJoin('dimensiones', 'dimensiones.id', '=', 'subdimensiones.id_dimension')
            ->paginate();

        return view('subdimension.index', compact('subdimensiones'))
            ->with('i', ($request->input('page', 1) - 1) * $subdimensiones->perPage());
    }

    public function create(): View
    {
        $subdimension = new Subdimension();

        return view('subdimension.create', compact('subdimension'));
    }

    public function store(SubdimensionRequest $request): RedirectResponse
    {
        Subdimension::create($request->validated());

        return Redirect::route('subdimension.index')
            ->with('success', 'Subdimension creada satisfactoriamente.');
    }

    public function show($id): View
    {
        $subdimension = Subdimension::find($id);

        return view('subdimension.show', compact('subdimension'));
    }

    public function edit($id): View
    {
        $subdimension = Subdimension::find($id);

        return view('subdimension.edit', compact('subdimension'));
    }

    public function update(SubdimensionRequest $request, Subdimension $subdimension): RedirectResponse
    {
        $subdimension->update($request->validated());

        return Redirect::route('subdimension.index')
            ->with('success', 'Subdimension Actualizada satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        Subdimension::find($id)->delete();

        return Redirect::route('subdimension.index')
            ->with('success', 'Subdimension Eliminada satisfactoriamente');
    }
}
