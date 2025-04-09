<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NivelesTerciario
 *
 * @property $id
 * @property $id_nivel_secundario
 * @property $id_rol
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property NivelesSecundario $nivelesSecundario
 * @property Roles $roles
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class NivelesTerciario extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_nivel_secundario', 'id_rol', 'nombre', 'descripcion', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nivelesSecundario()
    {
        return $this->belongsTo(\App\Models\NivelesSecundario::class, 'id_nivel_secundario', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roles()
    {
        return $this->belongsTo(\App\Models\Roles::class, 'id_rol', 'id');
    }
    
}
