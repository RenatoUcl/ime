<?php

namespace App\Http\Controllers;

use App\Models\NivelesTerciario;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\NivelesTerciarioRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class NivelesTerciarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $nivelesTerciarios = NivelesTerciario::paginate();

        return view('niveles-terciario.index', compact('nivelesTerciarios'))
            ->with('i', ($request->input('page', 1) - 1) * $nivelesTerciarios->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $nivelesTerciario = new NivelesTerciario();

        return view('niveles-terciario.create', compact('nivelesTerciario'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NivelesTerciarioRequest $request): RedirectResponse
    {
        NivelesTerciario::create($request->validated());

        return Redirect::route('niveles-terciarios.index')
            ->with('success', 'NivelesTerciario created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $nivelesTerciario = NivelesTerciario::find($id);

        return view('niveles-terciario.show', compact('nivelesTerciario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $nivelesTerciario = NivelesTerciario::find($id);

        return view('niveles-terciario.edit', compact('nivelesTerciario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(NivelesTerciarioRequest $request, NivelesTerciario $nivelesTerciario): RedirectResponse
    {
        $nivelesTerciario->update($request->validated());

        return Redirect::route('niveles-terciarios.index')
            ->with('success', 'NivelesTerciario updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        NivelesTerciario::find($id)->delete();

        return Redirect::route('niveles-terciarios.index')
            ->with('success', 'NivelesTerciario deleted successfully');
    }
}
