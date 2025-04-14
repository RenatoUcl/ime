<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Pregunta
 *
 * @property $id
 * @property $id_encuesta
 * @property $id_subdimension
 * @property $texto
 * @property $tipo
 * @property $created_at
 * @property $updated_at
 *
 * @property Encuesta $encuesta
 * @property Subdimensione $subdimensione
 * @property Alternativa[] $alternativas
 * @property Respuesta[] $respuestas
 * @property Tipo 
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Pregunta extends Model
{
    protected $perPage = 20;
    protected $table = 'preguntas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_encuesta', 'id_subdimension', 'texto', 'tipo','posicion', 'id_subdimension'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function encuesta()
    {
        return $this->belongsTo(\App\Models\Encuesta::class, 'id_encuesta', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subdimensione()
    {
        return $this->belongsTo(\App\Models\Subdimension::class, 'id_subdimension', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function alternativas()
    {
        return $this->hasMany(\App\Models\Alternativa::class, 'id_pregunta');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function respuestas()
    {
        return $this->hasMany(\App\Models\Respuesta::class, 'id', 'id_pregunta');
    }
    
}
