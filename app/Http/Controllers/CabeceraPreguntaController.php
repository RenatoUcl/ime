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
    public function index(Request $request): View
    {
        $cabeceraPreguntas = CabeceraPregunta::with('encuesta')->paginate();
        return view('cabecera-pregunta.index', compact('cabeceraPreguntas'))
            ->with('i', ($request->input('page', 1) - 1) * $cabeceraPreguntas->perPage());
    }

    public function create($id_encuesta): View
    {
        $cabeceraPregunta = new CabeceraPregunta();
        $id = $id_encuesta;
        return view('cabecera-pregunta.create', compact('cabeceraPregunta','id'));
    }

    public function store(CabeceraPreguntaRequest $request): RedirectResponse
    {
        CabeceraPregunta::create($request->validated());
        return Redirect::route('encuesta.index')
            ->with('success', 'CabeceraPregunta creada satisfactoriamente.');
    }

    public function show($id): View
    {
        $cabeceraPregunta = CabeceraPregunta::findOrFail($id);
        return view('cabecera-pregunta.show', compact('cabeceraPregunta'));
    }

    public function edit($id): View
    {
        $cabeceraPregunta = CabeceraPregunta::findOrFail($id);
        return view('cabecera-pregunta.edit', compact('cabeceraPregunta'));
    }

    public function update(CabeceraPreguntaRequest $request, CabeceraPregunta $cabeceraPregunta): RedirectResponse
    {
        $cabeceraPregunta->update($request->validated());
        return Redirect::route('cabecera-pregunta.index')
            ->with('success', 'CabeceraPregunta actualizada satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $cabeceraPregunta = CabeceraPregunta::findOrFail($id);
        $cabeceraPregunta->delete();
        return Redirect::route('cabecera-pregunta.index')
            ->with('success', 'CabeceraPregunta eliminada satisfactoriamente');
    }
}
