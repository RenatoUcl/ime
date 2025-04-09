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
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $cabeceraAlternativas = CabeceraAlternativa::paginate();

        return view('cabecera-alternativa.index', compact('cabeceraAlternativas'))
            ->with('i', ($request->input('page', 1) - 1) * $cabeceraAlternativas->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $cabeceraAlternativa = new CabeceraAlternativa();

        return view('cabecera-alternativa.create', compact('cabeceraAlternativa'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CabeceraAlternativaRequest $request): RedirectResponse
    {
        CabeceraAlternativa::create($request->validated());

        return Redirect::route('cabecera-alternativas.index')
            ->with('success', 'CabeceraAlternativa created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $cabeceraAlternativa = CabeceraAlternativa::find($id);

        return view('cabecera-alternativa.show', compact('cabeceraAlternativa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $cabeceraAlternativa = CabeceraAlternativa::find($id);

        return view('cabecera-alternativa.edit', compact('cabeceraAlternativa'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CabeceraAlternativaRequest $request, CabeceraAlternativa $cabeceraAlternativa): RedirectResponse
    {
        $cabeceraAlternativa->update($request->validated());

        return Redirect::route('cabecera-alternativas.index')
            ->with('success', 'CabeceraAlternativa updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        CabeceraAlternativa::find($id)->delete();

        return Redirect::route('cabecera-alternativas.index')
            ->with('success', 'CabeceraAlternativa deleted successfully');
    }
}
