<?php

namespace App\Http\Controllers;

use App\Models\MensajesEstado;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MensajesEstadoRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MensajesEstadoController extends Controller
{
    public function index(Request $request): View
    {
        $mensajesEstados = MensajesEstado::paginate();
        return view('mensajes-estado.index', compact('mensajesEstados'))
            ->with('i', ($request->input('page', 1) - 1) * $mensajesEstados->perPage());
    }

    public function create(): View
    {
        $mensajesEstado = new MensajesEstado();
        return view('mensajes-estado.create', compact('mensajesEstado'));
    }

    public function store(MensajesEstadoRequest $request): RedirectResponse
    {
        MensajesEstado::create($request->validated());
        return Redirect::route('mensajes-estado.index')
            ->with('success', 'MensajesEstado creado satisfactoriamente.');
    }

    public function show($id): View
    {
        $mensajesEstado = MensajesEstado::findOrFail($id);
        return view('mensajes-estado.show', compact('mensajesEstado'));
    }

    public function edit($id): View
    {
        $mensajesEstado = MensajesEstado::findOrFail($id);
        return view('mensajes-estado.edit', compact('mensajesEstado'));
    }

    public function update(MensajesEstadoRequest $request, MensajesEstado $mensajesEstado): RedirectResponse
    {
        $mensajesEstado->update($request->validated());
        return Redirect::route('mensajes-estado.index')
            ->with('success', 'MensajesEstado actualizado satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $mensajesEstado = MensajesEstado::findOrFail($id);
        $mensajesEstado->delete();
        return Redirect::route('mensajes-estado.index')
            ->with('success', 'MensajesEstado eliminado satisfactoriamente');
    }
}
