<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NivelesSecundario
 *
 * @property $id
 * @property $id_nivel_primario
 * @property $id_rol
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property NivelesPrimario $nivelesPrimario
 * @property Roles $roles
 * @property NivelesTerciario[] $nivelesTerciarios
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class NivelesSecundario extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_nivel_primario', 'id_rol', 'nombre', 'descripcion', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function nivelesPrimario()
    {
        return $this->belongsTo(\App\Models\NivelesPrimario::class, 'id_nivel_primario', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roles()
    {
        return $this->belongsTo(\App\Models\Roles::class, 'id_rol', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nivelesTerciarios()
    {
        return $this->hasMany(\App\Models\NivelesTerciario::class, 'id', 'id_nivel_secundario');
    }
    
}
