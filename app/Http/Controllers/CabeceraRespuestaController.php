<?php

namespace App\Http\Controllers;

use App\Models\CabeceraRespuesta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CabeceraRespuestaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CabeceraRespuestaController extends Controller
{
    public function index(Request $request): View
    {
        $cabeceraRespuestas = CabeceraRespuesta::paginate();
        return view('cabecera-respuesta.index', compact('cabeceraRespuestas'))
            ->with('i', ($request->input('page', 1) - 1) * $cabeceraRespuestas->perPage());
    }

    public function create(): View
    {
        $cabeceraRespuesta = new CabeceraRespuesta();
        return view('cabecera-respuesta.create', compact('cabeceraRespuesta'));
    }

    public function store(CabeceraRespuestaRequest $request): RedirectResponse
    {
        CabeceraRespuesta::create($request->validated());
        return Redirect::route('cabecera-respuesta.index')
            ->with('success', 'CabeceraRespuesta creada satisfactoriamente.');
    }

    public function show($id): View
    {
        $cabeceraRespuesta = CabeceraRespuesta::findOrFail($id);
        return view('cabecera-respuesta.show', compact('cabeceraRespuesta'));
    }

    public function edit($id): View
    {
        $cabeceraRespuesta = CabeceraRespuesta::findOrFail($id);
        return view('cabecera-respuesta.edit', compact('cabeceraRespuesta'));
    }

    public function update(CabeceraRespuestaRequest $request, CabeceraRespuesta $cabeceraRespuesta): RedirectResponse
    {
        $cabeceraRespuesta->update($request->validated());
        return Redirect::route('cabecera-respuesta.index')
            ->with('success', 'CabeceraRespuesta actualizada satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $cabeceraRespuesta = CabeceraRespuesta::findOrFail($id);
        $cabeceraRespuesta->delete();
        return Redirect::route('cabecera-respuesta.index')
            ->with('success', 'CabeceraRespuesta eliminada satisfactoriamente');
    }
}
