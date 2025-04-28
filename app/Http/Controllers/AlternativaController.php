<?php

namespace App\Http\Controllers;

use App\Models\Alternativa;
use App\Models\Pregunta;
use App\Models\Respuesta;

use App\Http\Requests\AlternativaRequest;
use App\Http\Requests\PreguntaRequest;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AlternativaController extends Controller
{
    public function index(Request $request): View
    {
        $alternativas = Alternativa::paginate();
        return view('alternativa.index', compact('alternativas'))
            ->with('i', ($request->input('page', 1) - 1) * $alternativas->perPage());
    }

    public function create(): View
    {
        $alternativa = new Alternativa();
        return view('alternativa.create', compact('alternativa'));
    }

    public function store(AlternativaRequest $request): RedirectResponse
    {
        Alternativa::create($request->validated());
        return Redirect::route('alternativas.index')
            ->with('success', 'Alternativa created successfully.');
    }

    public function show($id): View
    {
        $alternativa = Alternativa::find($id);
        return view('alternativa.show', compact('alternativa'));
    }

    public function edit($id): View
    {
        $alternativa = Alternativa::find($id);
        return view('alternativa.edit', compact('alternativa'));
    }

    public function update(AlternativaRequest $request,Alternativa $alternativa): RedirectResponse
    {
        $alternativa->update($request->validated());
        return Redirect::route('encuesta.index')
            ->with('success', 'Alternativa updated successfully');
    }

    public function disabled($id): RedirectResponse
    {
        $item = Alternativa::find($id);
        $id_preg = $item->id_pregunta;
        $pregunta = Pregunta::find($id_preg);
        $id_encuesta = $pregunta->id_encuesta;
        
        $respuesta = Respuesta::where('id_pregunta',$id_preg)->get();
        $count = $respuesta->count();

        if ($count > 0){
            return Redirect::route('encuesta.edit',$id_encuesta)
            ->with('warning', 'La Alternativa no puede ser eliminada, por que contienen respuestas asociadas.');
        } else {
            return Redirect::route('encuesta.edit',$id_encuesta)
            ->with('success', 'Encuesta desactivado satisfactoriamente');
        }
    }

    public function destroy($id): RedirectResponse
    {
        Alternativa::find($id)->delete();

        return Redirect::route('alternativa.index')
            ->with('success', 'Alternativa deleted successfully');
    }
}