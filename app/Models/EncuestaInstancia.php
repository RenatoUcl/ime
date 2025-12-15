<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncuestaInstancia extends Model
{
    protected $fillable = [
        'id_encuesta',
        'nombre_periodo',
        'fecha_desde',
        'fecha_hasta',
        'estado'
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta');
    }
}
