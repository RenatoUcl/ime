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

    protected $fillable = ['id_linea', 'nombre', 'descripcion', 'posicion', 'estado'];

    public function linea()
    {
        return $this->belongsTo(LineasProgramaticas::class, 'id_linea');
    }

    public function subdimensiones()
    {
        return $this->hasMany(Subdimension::class, 'id_dimension');
    }
    
}
