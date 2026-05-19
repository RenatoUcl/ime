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
    public function index(Request $request): View
    {
        $encuestasUsuarios = EncuestasUsuario::with(['encuesta', 'user'])->paginate();

        return view('encuestas-usuario.index', compact('encuestasUsuarios'))
            ->with('i', ($request->input('page', 1) - 1) * $encuestasUsuarios->perPage());
    }

    public function create(): View
    {
        $encuestasUsuario = new EncuestasUsuario();

        return view('encuestas-usuario.create', compact('encuestasUsuario'));
    }

    public function store(EncuestasUsuarioRequest $request): RedirectResponse
    {
        EncuestasUsuario::create($request->validated());

        return Redirect::route('encuestas-usuarios.index')
            ->with('success', 'EncuestasUsuario creado satisfactoriamente.');
    }

    public function show($id): View
    {
        $encuestasUsuario = EncuestasUsuario::findOrFail($id);
        return view('encuestas-usuario.show', compact('encuestasUsuario'));
    }

    public function edit($id): View
    {
        $encuestasUsuario = EncuestasUsuario::findOrFail($id);
        return view('encuestas-usuario.edit', compact('encuestasUsuario'));
    }

    public function update(EncuestasUsuarioRequest $request, EncuestasUsuario $encuestasUsuario): RedirectResponse
    {
        $encuestasUsuario->update($request->validated());
        return Redirect::route('encuestas-usuarios.index')
            ->with('success', 'EncuestasUsuario actualizado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $encuestasUsuario = EncuestasUsuario::findOrFail($id);
        $encuestasUsuario->delete();
        return Redirect::route('encuestas-usuarios.index')
            ->with('success', 'EncuestasUsuario eliminado satisfactoriamente');
    }
}
