<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Respuesta
 *
 * @property $id
 * @property $id_pregunta
 * @property $id_alternativa
 * @property $texto
 * @property $valor
 * @property $nivel
 * @property $created_at
 * @property $updated_at
 *
 * @property Alternativa $alternativa
 * @property Pregunta $pregunta
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Respuesta extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_pregunta', 'id_alternativa', 'id_usuario', 'texto', 'valor', 'nivel'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function alternativa()
    {
        return $this->belongsTo(\App\Models\Alternativa::class, 'id_alternativa', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pregunta()
    {
        return $this->belongsTo(\App\Models\Pregunta::class, 'id_pregunta', 'id');
    }
    
}
