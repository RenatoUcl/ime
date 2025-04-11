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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cabeceraRespuestas = CabeceraRespuesta::paginate();

        return view('cabecera-respuesta.index', compact('cabeceraRespuestas'))
            ->with('i', ($request->input('page', 1) - 1) * $cabeceraRespuestas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cabeceraRespuesta = new CabeceraRespuesta();

        return view('cabecera-respuesta.create', compact('cabeceraRespuesta'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CabeceraRespuestaRequest $request): RedirectResponse
    {
        CabeceraRespuesta::create($request->validated());

        return Redirect::route('cabecera-respuestas.index')
            ->with('success', 'CabeceraRespuesta created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cabeceraRespuesta = CabeceraRespuesta::find($id);

        return view('cabecera-respuesta.show', compact('cabeceraRespuesta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cabeceraRespuesta = CabeceraRespuesta::find($id);

        return view('cabecera-respuesta.edit', compact('cabeceraRespuesta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CabeceraRespuestaRequest $request, CabeceraRespuesta $cabeceraRespuesta): RedirectResponse
    {
        $cabeceraRespuesta->update($request->validated());

        return Redirect::route('cabecera-respuestas.index')
            ->with('success', 'CabeceraRespuesta updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CabeceraRespuesta::find($id)->delete();

        return Redirect::route('cabecera-respuestas.index')
            ->with('success', 'CabeceraRespuesta deleted successfully');
    }
}
