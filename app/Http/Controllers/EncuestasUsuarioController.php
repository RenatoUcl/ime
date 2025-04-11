<?php

namespace App\Http\Controllers;

use App\Models\EncuestasUsuario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\EncuestasUsuarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class EncuestasUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $encuestasUsuarios = EncuestasUsuario::paginate();

        return view('encuestas-usuario.index', compact('encuestasUsuarios'))
            ->with('i', ($request->input('page', 1) - 1) * $encuestasUsuarios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $encuestasUsuario = new EncuestasUsuario();

        return view('encuestas-usuario.create', compact('encuestasUsuario'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EncuestasUsuarioRequest $request): RedirectResponse
    {
        EncuestasUsuario::create($request->validated());

        return Redirect::route('encuestas-usuarios.index')
            ->with('success', 'EncuestasUsuario created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $encuestasUsuario = EncuestasUsuario::find($id);

        return view('encuestas-usuario.show', compact('encuestasUsuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $encuestasUsuario = EncuestasUsuario::find($id);

        return view('encuestas-usuario.edit', compact('encuestasUsuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EncuestasUsuarioRequest $request, EncuestasUsuario $encuestasUsuario): RedirectResponse
    {
        $encuestasUsuario->update($request->validated());

        return Redirect::route('encuestas-usuarios.index')
            ->with('success', 'EncuestasUsuario updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        EncuestasUsuario::find($id)->delete();

        return Redirect::route('encuestas-usuarios.index')
            ->with('success', 'EncuestasUsuario deleted successfully');
    }
}
