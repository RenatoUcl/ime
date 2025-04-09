<?php

namespace App\Http\Controllers;

use App\Models\Mensaje;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Http\Requests\MensajeRequest;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class MensajeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $mensajes = Mensaje::paginate();

        return view('mensaje.index', compact('mensajes'))
            ->with('i', ($request->input('page', 1) - 1) * $mensajes->perPage());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $mensaje = new Mensaje();

        return view('mensaje.create', compact('mensaje'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MensajeRequest $request): RedirectResponse
    {
        Mensaje::create($request->validated());

        return Redirect::route('mensajes.index')
            ->with('success', 'Mensaje created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id): View
    {
        $mensaje = Mensaje::find($id);

        return view('mensaje.show', compact('mensaje'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $mensaje = Mensaje::find($id);

        return view('mensaje.edit', compact('mensaje'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MensajeRequest $request, Mensaje $mensaje): RedirectResponse
    {
        $mensaje->update($request->validated());

        return Redirect::route('mensajes.index')
            ->with('success', 'Mensaje updated successfully');
    }

    public function destroy($id): RedirectResponse
    {
        Mensaje::find($id)->delete();

        return Redirect::route('mensajes.index')
            ->with('success', 'Mensaje deleted successfully');
    }
}
