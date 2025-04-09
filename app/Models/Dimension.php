<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Dimensione
 *
 * @property $id
 * @property $id_linea
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property LineasProgramatica $lineasProgramatica
 * @property Subdimensione[] $subdimensiones
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Dimension extends Model
{
    protected $table = "dimensiones";

    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_linea', 'nombre', 'descripcion', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lineasProgramatica()
    {
        return $this->belongsTo(\App\Models\LineasProgramaticas::class, 'id_linea', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subdimensiones()
    {
        return $this->hasMany(\App\Models\Subdimension::class, 'id', 'id_dimension');
    }
    
}
