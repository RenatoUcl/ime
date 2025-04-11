<?php

namespace App\Http\Controllers;

use App\Models\Roles;
use App\Models\Permiso;
use App\Http\Requests\RoleRequest;
use App\Http\Requests\PermisoRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class RolesController extends Controller
{
    public function index(Request $request): View
    {
        $roles = Roles::paginate();
        return view('role.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * $roles->perPage());
    }

    public function create(): View
    {
        $role = new Roles();
        return view('role.create', compact('role'));
    }

    public function store(Request $request)
    {
        $role = Roles::create($request->only(['nombre', 'descripcion']));
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }
        return $role;
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->only(['nombre', 'descripcion']));
        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }
        return $role;
    }

    public function show($id): View
    {
        $role = Roles::find($id);
        return view('role.show', compact('role'));
    }

    public function edit($id): View
    {
        $role = Roles::find($id);
        return view('role.edit', compact('role'));
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

    public function mostrar($id)
    {
        $rol = Roles::findOrFail($id);
        $permisos = Permiso::all();  // Obtener todos los permisos

        return view('role.asignar', compact('rol', 'permisos'));
    }

    public function asignar(Request $request, $id)
    {
        $rol = Roles::findOrFail($id);  // Encuentra el rol por ID
        $permisosIds = $request->input('permisos');  // Recibe los IDs de los permisos seleccionados desde el formulario

        // Asigna los permisos seleccionados al rol
        $rol->permisos()->sync($permisosIds);  // Puedes usar attach() o syncWithoutDetaching() también

        return Redirect::route('role.index')
               ->with('success', 'Permisos asignados correctamente al rol');
    }
}
