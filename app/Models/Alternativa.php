<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Alternativa
 *
 * @property $id
 * @property $id_pregunta
 * @property $id_dependencia
 * @property $texto
 * @property $valor
 * @property $created_at
 * @property $updated_at
 *
 * @property Pregunta $pregunta
 * @property Respuesta[] $respuestas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Alternativa extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_pregunta', 'id_dependencia', 'texto', 'valor' ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pregunta()
    {
        return $this->belongsTo(\App\Models\Pregunta::class, 'id_pregunta', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function respuestas()
    {
        return $this->hasMany(\App\Models\Respuesta::class, 'id', 'id_alternativa');
    }
    
}
