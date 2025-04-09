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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $mensajesRespuestas = MensajesRespuesta::paginate();

        return view('mensajes-respuesta.index', compact('mensajesRespuestas'))
            ->with('i', ($request->input('page', 1) - 1) * $mensajesRespuestas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $mensajesRespuesta = new MensajesRespuesta();

        return view('mensajes-respuesta.create', compact('mensajesRespuesta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MensajesRespuestaRequest $request): RedirectResponse
    {
        MensajesRespuesta::create($request->validated());

        return Redirect::route('mensajes-respuestas.index')
            ->with('success', 'MensajesRespuesta created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $mensajesRespuesta = MensajesRespuesta::find($id);

        return view('mensajes-respuesta.show', compact('mensajesRespuesta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $mensajesRespuesta = MensajesRespuesta::find($id);

        return view('mensajes-respuesta.edit', compact('mensajesRespuesta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MensajesRespuestaRequest $request, MensajesRespuesta $mensajesRespuesta): RedirectResponse
    {
        $mensajesRespuesta->update($request->validated());

        return Redirect::route('mensajes-respuestas.index')
            ->with('success', 'MensajesRespuesta updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        MensajesRespuesta::find($id)->delete();

        return Redirect::route('mensajes-respuestas.index')
            ->with('success', 'MensajesRespuesta deleted successfully');
    }
}
