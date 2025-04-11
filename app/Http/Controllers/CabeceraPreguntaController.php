<?php

namespace App\Http\Controllers;

use App\Models\CabeceraPregunta;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CabeceraPreguntaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CabeceraPreguntaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cabeceraPreguntas = CabeceraPregunta::paginate();

        return view('cabecera-pregunta.index', compact('cabeceraPreguntas'))
            ->with('i', ($request->input('page', 1) - 1) * $cabeceraPreguntas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id_encuesta): View
    {
        $cabeceraPregunta = new CabeceraPregunta();
        $id = $id_encuesta;
        return view('cabecera-pregunta.create', compact('cabeceraPregunta','id'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CabeceraPreguntaRequest $request): RedirectResponse
    {
        CabeceraPregunta::create($request->validated());

        return Redirect::route('encuesta.index')
            ->with('success', 'CabeceraPregunta created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cabeceraPregunta = CabeceraPregunta::find($id);

        return view('cabecera-pregunta.show', compact('cabeceraPregunta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cabeceraPregunta = CabeceraPregunta::find($id);

        return view('cabecera-pregunta.edit', compact('cabeceraPregunta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CabeceraPreguntaRequest $request, CabeceraPregunta $cabeceraPregunta): RedirectResponse
    {
        $cabeceraPregunta->update($request->validated());

        return Redirect::route('cabecera-pregunta.index')
            ->with('success', 'CabeceraPregunta updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CabeceraPregunta::find($id)->delete();

        return Redirect::route('cabecera-pregunta.index')
            ->with('success', 'CabeceraPregunta deleted successfully');
    }
}
