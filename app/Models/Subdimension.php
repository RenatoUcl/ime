<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Subdimensione
 *
 * @property $id
 * @property $id_dimension
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property Dimension $dimension
 * @property Pregunta[] $preguntas
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Subdimension extends Model
{
    protected $table = "subdimensiones";

    protected $perPage = 20;

    protected $fillable = ['id_dimension', 'nombre', 'descripcion', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function dimension()
    {
        return $this->belongsTo(\App\Models\Dimension::class, 'id_dimension', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function preguntas()
    {
        return $this->hasMany(\App\Models\Pregunta::class, 'id', 'id_subdimension');
    }
    
}
