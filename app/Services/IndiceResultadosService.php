<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class IndiceResultadosService
{
    /**
     * Promedio por dimensión
     */
    public static function porDimension(int $encuestaId, int $instanciaId)
    {
        return DB::table('respuestas as r')
            ->join('preguntas as p', 'p.id', '=', 'r.id_pregunta')
            ->join('subdimensiones as sd', 'sd.id', '=', 'p.id_subdimension')
            ->join('dimensiones as d', 'd.id', '=', 'sd.id_dimension')
            ->where('r.id_encuesta', $encuestaId)
            ->where('r.id_encuesta_instancia', $instanciaId)
            ->select(
                'd.id as id_dimension',
                'd.nombre',
                DB::raw('ROUND(AVG(r.valor),2) as promedio')
            )
            ->groupBy('d.id', 'd.nombre')
            ->orderBy('d.posicion')
            ->get();
    }

    /**
     * Promedio por subdimensión
     */
    public static function porSubdimension(int $encuestaId, int $instanciaId, int $dimensionId)
    {
        return DB::table('respuestas as r')
            ->join('preguntas as p', 'p.id', '=', 'r.id_pregunta')
            ->join('subdimensiones as sd', 'sd.id', '=', 'p.id_subdimension')
            ->where('r.id_encuesta', $encuestaId)
            ->where('r.id_encuesta_instancia', $instanciaId)
            ->where('sd.id_dimension', $dimensionId)
            ->select(
                'sd.id as id_subdimension',
                'sd.nombre as sdNombre',
                DB::raw('ROUND(AVG(r.valor),2) as promedio')
            )
            ->groupBy('sd.id', 'sd.nombre')
            ->orderBy('sd.posicion')
            ->get();
    }
}