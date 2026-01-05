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

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $userAuth = auth()->user()->load('roles');
        if (!$userAuth->hasRole('admin')) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        $search = $request->get('search');

        $users = User::when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('ap_paterno', 'like', "%{$search}%")
                    ->orWhere('ap_materno', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            })
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
            ->with('success', 'user created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $user = User::find($id);

        return view('usuarios.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $user = User::find($id);

        return view('usuarios.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    /*
    public function update(UserRequest $request, $user): RedirectResponse
    {
        $user->update($request->validated());

        return Redirect::route('usuarios.index')
            ->with('success', 'Rol actualizado satisfactoriamente');
    }
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
        $user = User::find($id);
        $user->estado = 0;
        $user->save();

        return Redirect::route('usuarios.index')
        ->with('success', 'user desactivado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();

        return Redirect::route('usuarios.index')
            ->with('success', 'user deleted successfully');
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

}
