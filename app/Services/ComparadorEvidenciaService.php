<?php

namespace App\Services;

use App\Models\MedioVerificacion;
use App\Models\EvaluacionSubdimension;

class ComparadorEvidenciaService
{

    public function comparar(
        $idEncuesta,
        $idSubdimension
    ){

    $evaluacion =
    EvaluacionSubdimension::where([
        'id_encuesta'=>$idEncuesta,
        'id_subdimension'=>$idSubdimension
    ])
    ->first();

    $medio =
    MedioVerificacion::where([
        'id_encuesta'=>$idEncuesta,
        'id_subdimension'=>$idSubdimension
    ])
    ->first();

    if(!$medio)
    {
        return null;
    }

    $archivos =
    $medio
    ->archivos()
    ->with('analisis')
    ->get();

    $total=0;
    $cantidad=0;

    foreach($archivos as $archivo)
    {
        if($archivo->analisis)
        {
            $total +=
            $archivo
            ->analisis
            ->puntaje;

            $cantidad++;
        }
    }

    $promedioDocumento =
    $cantidad
    ?
    round($total/$cantidad,2)
    :
    0;

    $brecha =
    $evaluacion->porcentaje
    -
    $promedioDocumento;

    return [
        'resultado_encuesta'=>
        $evaluacion->porcentaje,

        'resultado_documentos'=>
        $promedioDocumento,

        'brecha'=>
        $brecha,

        'alerta'=>
        $brecha > 20
        ];
    }

}
