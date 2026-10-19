<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedioVerificacionAnalisis extends Model
{
    protected $table='medios_verificacion_analisis';

    protected $casts=[
        'terminos_encontrados'=>'array',
        'terminos_no_encontrados'=>'array'
    ];

    protected $fillable=[
        'id_archivo',
        'terminos_encontrados',
        'terminos_no_encontrados',
        'puntaje',
        'resultado',
        'observacion'
    ];
}
