<?php

namespace App\Http\Controllers;

use App\Models\Permiso;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\PermisoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PermisoController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Permiso::class);

        $permisos = Permiso::paginate();
        return view('permiso.index', compact('permisos'))
            ->with('i', ($request->input('page', 1) - 1) * $permisos->perPage());
    }

    public function create(): View
    {
        $this->authorize('create', Permiso::class);

        $permiso = new Permiso();
        return view('permiso.create', compact('permiso'));
    }

    public function store(PermisoRequest $request): RedirectResponse
    {
        Permiso::create($request->validated());
        return Redirect::route('permiso.index')
            ->with('success', 'Permiso creado satisfactoriamente.');
    }

    public function show($id): View
    {
        $permiso = Permiso::findOrFail($id);
        return view('permiso.show', compact('permiso'));
    }

    public function edit($id): View
    {
        $permiso = Permiso::findOrFail($id);
        return view('permiso.edit', compact('permiso'));
    }

    public function update(PermisoRequest $request, Permiso $permiso): RedirectResponse
    {
        $permiso->update($request->validated());
        return Redirect::route('permiso.index')
            ->with('success', 'Permiso actualizado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $permiso = Permiso::findOrFail($id);
        $permiso->delete();
        return Redirect::route('permiso.index')
            ->with('success', 'Permiso eliminado satisfactoriamente');
    }
}
