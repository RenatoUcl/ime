<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $perPage = 20;

    protected $fillable = [
        'id_encuesta',
        'id_pregunta',
        'id_alternativa',
        'id_usuario',
        'valor',
        'nivel'
    ];

    public function alternativa()
    {
        return $this->belongsTo(\App\Models\Alternativa::class, 'id_alternativa', 'id');
    }
    
    public function pregunta()
    {
        return $this->belongsTo(\App\Models\Pregunta::class, 'id_pregunta', 'id');
    }
    
}
