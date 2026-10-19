<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluacionSubdimension extends Model
{
    protected $table='evaluacion_subdimensiones';

    protected $fillable=[
        'id_encuesta',
        'id_subdimension',
        'puntaje_obtenido',
        'puntaje_maximo',
        'porcentaje'
    ];
}
