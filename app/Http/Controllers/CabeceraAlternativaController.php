<?php

namespace App\Http\Controllers;

use App\Models\CabeceraAlternativa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\CabeceraAlternativaRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CabeceraAlternativaController extends Controller
{
    public function index(Request $request): View
    {
        $cabeceraAlternativas = CabeceraAlternativa::with('cabeceraPregunta')->paginate();
        return view('cabecera-alternativa.index', compact('cabeceraAlternativas'))
            ->with('i', ($request->input('page', 1) - 1) * $cabeceraAlternativas->perPage());
    }

    public function create(): View
    {
        $cabeceraAlternativa = new CabeceraAlternativa();
        return view('cabecera-alternativa.create', compact('cabeceraAlternativa'));
    }

    public function store(CabeceraAlternativaRequest $request): RedirectResponse
    {
        CabeceraAlternativa::create($request->validated());
        return Redirect::route('cabecera-alternativa.index')
            ->with('success', 'CabeceraAlternativa creada satisfactoriamente.');
    }

    public function show($id): View
    {
        $cabeceraAlternativa = CabeceraAlternativa::findOrFail($id);
        return view('cabecera-alternativa.show', compact('cabeceraAlternativa'));
    }

    public function edit($id): View
    {
        $cabeceraAlternativa = CabeceraAlternativa::findOrFail($id);
        return view('cabecera-alternativa.edit', compact('cabeceraAlternativa'));
    }

    public function update(CabeceraAlternativaRequest $request, CabeceraAlternativa $cabeceraAlternativa): RedirectResponse
    {
        $cabeceraAlternativa->update($request->validated());
        return Redirect::route('cabecera-alternativa.index')
            ->with('success', 'CabeceraAlternativa actualizada satisfactoriamente');
    }

    public function destroy($id): RedirectResponse
    {
        $cabeceraAlternativa = CabeceraAlternativa::findOrFail($id);
        $cabeceraAlternativa->delete();
        return Redirect::route('cabecera-alternativa.index')
            ->with('success', 'CabeceraAlternativa eliminada satisfactoriamente');
    }
}
