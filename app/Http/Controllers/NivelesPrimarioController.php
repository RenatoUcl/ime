<?php

namespace App\Http\Controllers;

use App\Models\NivelesPrimario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\NivelesPrimarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class NivelesPrimarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $nivelesPrimarios = NivelesPrimario::paginate();

        return view('niveles-primario.index', compact('nivelesPrimarios'))
            ->with('i', ($request->input('page', 1) - 1) * $nivelesPrimarios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $nivelesPrimario = new NivelesPrimario();

        return view('niveles-primario.create', compact('nivelesPrimario'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NivelesPrimarioRequest $request): RedirectResponse
    {
        NivelesPrimario::create($request->validated());

        return Redirect::route('niveles-primario.index')
            ->with('success', 'NivelesPrimario created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $nivelesPrimario = NivelesPrimario::find($id);

        return view('niveles-primario.show', compact('nivelesPrimario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $nivelesPrimario = NivelesPrimario::find($id);

        return view('niveles-primario.edit', compact('nivelesPrimario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NivelesPrimarioRequest $request, NivelesPrimario $nivelesPrimario): RedirectResponse
    {
        $nivelesPrimario->update($request->validated());

        return Redirect::route('niveles-primario.index')
            ->with('success', 'NivelesPrimario updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        NivelesPrimario::find($id)->delete();

        return Redirect::route('niveles-primario.index')
            ->with('success', 'NivelesPrimario deleted successfully');
    }
}
