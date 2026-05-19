<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Respuesta extends Model
{
    use SoftDeletes;

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

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }
}