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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $mensajesEstados = MensajesEstado::paginate();

        return view('mensajes-estado.index', compact('mensajesEstados'))
            ->with('i', ($request->input('page', 1) - 1) * $mensajesEstados->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $mensajesEstado = new MensajesEstado();

        return view('mensajes-estado.create', compact('mensajesEstado'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MensajesEstadoRequest $request): RedirectResponse
    {
        MensajesEstado::create($request->validated());

        return Redirect::route('mensajes-estados.index')
            ->with('success', 'MensajesEstado created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $mensajesEstado = MensajesEstado::find($id);

        return view('mensajes-estado.show', compact('mensajesEstado'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $mensajesEstado = MensajesEstado::find($id);

        return view('mensajes-estado.edit', compact('mensajesEstado'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MensajesEstadoRequest $request, MensajesEstado $mensajesEstado): RedirectResponse
    {
        $mensajesEstado->update($request->validated());

        return Redirect::route('mensajes-estados.index')
            ->with('success', 'MensajesEstado updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        MensajesEstado::find($id)->delete();

        return Redirect::route('mensajes-estados.index')
            ->with('success', 'MensajesEstado deleted successfully');
    }
}
