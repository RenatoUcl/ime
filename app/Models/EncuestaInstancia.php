<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EncuestaInstancia extends Model
{
    protected $table = 'encuestas_instancias';

    protected $fillable = [
        'id_encuesta',
        'nombre_periodo',
        'fecha_desde',
        'fecha_hasta',
        'estado',
    ];

    protected $casts = [
        'fecha_desde' => 'date',
        'fecha_hasta' => 'date',
        'estado' => 'boolean',
    ];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta');
    }
}