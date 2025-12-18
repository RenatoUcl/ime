<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Respuesta extends Model
{
    protected $table = 'respuestas';

    protected $fillable = [
        'id_encuesta',
        'id_encuesta_instancia',
        'id_pregunta',
        'id_alternativa',
        'id_usuario',
        'valor',
        'nivel'
    ];

    public function alternativa()
    {
        return $this->belongsTo(Alternativa::class, 'id_alternativa', 'id');
    }

    public function pregunta()
    {
        return $this->belongsTo(Pregunta::class, 'id_pregunta', 'id');
    }
}