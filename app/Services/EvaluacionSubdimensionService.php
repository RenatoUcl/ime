<?php

namespace App\Services;

use App\Models\Pregunta;
use App\Models\Respuesta;
use App\Models\EvaluacionSubdimension;

class EvaluacionSubdimensionService
{

    public function calcular(
        $idEncuesta,
        $idSubdimension
    ){
        /*
        Obtenemos preguntas
        de la subdimension
        */

        $preguntas =
        Pregunta::where(
            'id_subdimension',
            $idSubdimension
        )
        ->where(
            'id_encuesta',
            $idEncuesta
        )
        ->get();

        $idsPreguntas =
        $preguntas
        ->pluck('id');

        /*
        Respuestas realizadas
        */

        $respuestas =
        Respuesta::whereIn(
            'id_pregunta',
            $idsPreguntas
        )
        ->get();

        /*
        Puntaje obtenido
        */

        $obtenido =
        $respuestas
        ->sum('valor');

        /*
        Puntaje máximo posible
        */

        $maximo = 0;

        foreach($preguntas as $pregunta)
        {

            $valorMaximo =
            Respuesta::where(
                'id_pregunta',
                $pregunta->id
            )
            ->max('valor');

            $maximo +=
            $valorMaximo;

        }

        $porcentaje =
        $maximo > 0
        ?
        round(
            ($obtenido/$maximo)*100,
            2
        )
        :
        0;

        return EvaluacionSubdimension::updateOrCreate(

        [
            'id_encuesta'=>$idEncuesta,
            'id_subdimension'=>$idSubdimension
        ],

        [
            'puntaje_obtenido'=>$obtenido,
            'puntaje_maximo'=>$maximo,
            'porcentaje'=>$porcentaje
        ]

        );

    }

}
