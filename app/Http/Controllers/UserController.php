<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Roles;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

// Requeridos para la importación de usuarios desde Excel/CSV
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $search = $request->get('search');

        $users = User::where('estado',1)
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('ap_paterno', 'like', "%{$search}%")
                    ->orWhere('ap_materno', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->with('roles')
            ->orderBy('id', 'asc')
            ->paginate(20);

        return view('usuarios.index', compact('users', 'search'))
            ->with('i', ($request->input('page', 1) - 1) * $users->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $user = new User();

        return view('usuarios.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request): RedirectResponse
    {
        User::create($request->validated());
        return Redirect::route('usuarios.index')
            ->with('success', 'Usuario creado satisfactoriamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::findOrFail($id);

        return view('usuarios.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $user = User::findOrFail($id);

        return view('usuarios.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UserUpdateRequest $request, $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return Redirect::route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function disabled($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->estado = 0;
        $user->save();

        return Redirect::route('usuarios.index')
        ->with('success', 'user desactivado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return Redirect::route('usuarios.index')
            ->with('success', 'Usuario eliminado satisfactoriamente');
    }

    // Mostrar formulario para asignar roles
    public function mostrarRoles($id)
    {
        $usuario = User::findOrFail($id);
        $roles = Roles::all();
        return view('usuarios.roles', compact('usuario', 'roles'));
    }

    // Guardar asignación de roles
    public function asignarRoles(Request $request, $id)
    {
        $usuario = User::findOrFail($id);

        //dd($request->toArray());

        $usuario->roles()->sync($request->input('rol'));

        //dd($request->input('rol'), $usuario->roles()->pluck('id')->toArray());


        return redirect()->route('usuarios.index')->with('success', 'Roles asignados correctamente.');
    }
    
    /**
     * Muestra la vista para subir el archivo CSV.
     */
    public function showImportForm(): View
    {
        return view('usuarios.import');
    }

    /**
     * Procesa la carga e importación masiva desde el archivo CSV.
     */
    public function import(Request $request): RedirectResponse
    {
        // Aumentar el tiempo máximo de ejecución a 5 minutos (300 segundos)
        set_time_limit(300);
        ini_set('memory_limit', '512M');
        
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:10240', // Máximo 10 MB
        ], [
            'file.required' => 'Debes adjuntar un archivo CSV.',
            'file.mimes'    => 'El archivo debe tener formato .csv.',
            'file.max'      => 'El archivo no puede pesar más de 10 MB.',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));

            return Redirect::route('usuarios.index')
                ->with('success', 'Usuarios importados y procesados satisfactoriamente.');
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            return back()->with('import_errors', $failures);
        }
    }

}
