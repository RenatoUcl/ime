<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $roles = Roles::paginate();

        return view('role.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * $roles->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $role = new Roles();

        return view('role.create', compact('role'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request): RedirectResponse
    {
        Roles::create($request->validated());

        return Redirect::route('role.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $role = Roles::find($id);

        return view('role.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $role = Roles::find($id);

        return view('role.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, $role): RedirectResponse
    {
        //$role->update($request->validated());

        $rol = Roles::find($role);

        $rol->nombre = $request->nombre;
        $rol->descripcion = $request->descripcion;
        $rol->estado = $request->estado;

        $rol->save();

        return Redirect::route('role.index')
            ->with('success', 'Rol actualizado satisfactoriamente');
    }

    public function disabled($id): RedirectResponse
    {
        $rol = Roles::find($id);
        $rol->estado = 0;
        $rol->save();

        return Redirect::route('role.index')
        ->with('success', 'Rol desactivado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        Roles::find($id)->delete();

        return Redirect::route('role.index')
            ->with('success', 'Role deleted successfully');
    }
}
