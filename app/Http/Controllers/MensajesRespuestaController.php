<?php

namespace App\Http\Controllers;

use App\Models\MensajesRespuesta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MensajesRespuestaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MensajesRespuestaController extends Controller
{
    public function index(Request $request): View
    {
        $mensajesRespuestas = MensajesRespuesta::paginate();
        return view('mensajes-respuesta.index', compact('mensajesRespuestas'))
            ->with('i', ($request->input('page', 1) - 1) * $mensajesRespuestas->perPage());
    }

    public function create(): View
    {
        $mensajesRespuesta = new MensajesRespuesta();
        return view('mensajes-respuesta.create', compact('mensajesRespuesta'));
    }

    public function store(MensajesRespuestaRequest $request): RedirectResponse
    {
        MensajesRespuesta::create($request->validated());
        return Redirect::route('mensajes-respuesta.index')
            ->with('success', 'MensajesRespuesta creada satisfactoriamente.');
    }

    public function show($id): View
    {
        $mensajesRespuesta = MensajesRespuesta::findOrFail($id);
        return view('mensajes-respuesta.show', compact('mensajesRespuesta'));
    }

    public function edit($id): View
    {
        $mensajesRespuesta = MensajesRespuesta::findOrFail($id);
        return view('mensajes-respuesta.edit', compact('mensajesRespuesta'));
    }

    public function update(MensajesRespuestaRequest $request, MensajesRespuesta $mensajesRespuesta): RedirectResponse
    {
        $mensajesRespuesta->update($request->validated());
        return Redirect::route('mensajes-respuesta.index')
            ->with('success', 'MensajesRespuesta actualizada satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $mensajesRespuesta = MensajesRespuesta::findOrFail($id);
        $mensajesRespuesta->delete();
        return Redirect::route('mensajes-respuesta.index')
            ->with('success', 'MensajesRespuesta eliminada satisfactoriamente');
    }
}
