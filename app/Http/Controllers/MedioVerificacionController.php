<?php

namespace App\Http\Controllers;

use App\Models\MedioVerificacion;
use App\Models\MedioVerificacionArchivo;
use App\Services\MedioVerificacionService;
use App\Models\Dimension;
use App\Models\Subdimension;

use Illuminate\Http\Request;

class MedioVerificacionController extends Controller
{
    public function index()
    {
        $medios = MedioVerificacion::with(['subdimension','archivos.analisis'])->latest()->get();
        return view('medios-verificacion.index', compact('medios'));
    }

    public function create()
    {
        $subdimensiones = Subdimension::with('dimension')->get();
        return view('medios-verificacion.create', compact('subdimensiones'));
    }

    public function store(Request $request)
    {
        $data =
        $request->validate([
            'id_encuesta'=>'required',
            'id_dimension'=>'required',
            'id_subdimension'=>'required',
            'titulo'=>'required',
        ]);

        MedioVerificacion::create([
            $data
        ]);

        return redirect()
            ->route('medios-verificacion.index');
        
    }

    public function show($id)
    {
        $medio = MedioVerificacion::with(['archivos.analisis','subdimension'])->findOrFail($id);
        return view('medios-verificacion.show', compact('medio'));
    }

    public function subirArchivo(Request $request,$id)
    {
        $request->validate([
            'archivo'=>'required|mimes:pdf,doc,docx',
        ]);

        $medio = MedioVerificacion::findOrFail($id);

        $path =
            $request
                ->file('archivo')
                ->store(
                    'medios_verificacion',
                    'public'
                );

        $archivo =
            $medio->archivos()->create([
                'archivo'=>$path,
                'nombre_original'=>$request->file('archivo')->getClientOriginalName(),
                'extension'=>$request->file('archivo')->extension()
            ]);

        app(MedioVerificacionService::class)
            ->analizar($archivo);

        return back()
            ->with(
            'success',
            'Documento analizado correctamente'
            );
    }
    
}
