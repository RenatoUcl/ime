<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model
{
    protected $perPage = 20;
    protected $table = 'preguntas';

    protected $fillable = ['id_encuesta', 'id_subdimension', 'texto', 'tipo','posicion', 'id_subdimension'];

    public function encuesta()
    {
        return $this->belongsTo(Encuesta::class, 'id_encuesta', 'id');
    }
    
    public function subdimension()
    {
        return $this->belongsTo(Subdimension::class, 'id_subdimension', 'id');
    }
    
    public function alternativas()
    {
        return $this->hasMany(Alternativa::class, 'id_pregunta');
    }
    
    public function respuestas()
    {
        return $this->hasMany(Respuesta::class, 'id', 'id_pregunta');
    }

    public function respuestaUsuario()
    {
        return $this->hasOne(Respuesta::class, 'id_pregunta', 'id')
            ->where('id_usuario', auth()->id());
    }

    public function dependencia()
    {
        return $this->belongsTo(Pregunta::class, 'id_dependencia');
    }

    public function subdimension()
    {
        return $this->belongsTo(Subdimension::class, 'id_subdimension');
    }

}
