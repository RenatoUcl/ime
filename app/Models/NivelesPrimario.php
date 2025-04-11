<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class NivelesPrimario
 *
 * @property $id
 * @property $id_linea_programatica
 * @property $id_rol
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property LineasProgramaticas $lineasProgramaticas
 * @property Role $role
 * @property NivelesSecundario[] $nivelesSecundarios
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class NivelesPrimario extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['id_linea_programatica', 'id_rol', 'nombre', 'descripcion', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lineasProgramatica()
    {
        return $this->belongsTo(\App\Models\LineasProgramaticas::class, 'id_linea_programatica', 'id');
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
    public function nivelesSecundarios()
    {
        return $this->hasMany(\App\Models\NivelesSecundario::class, 'id', 'id_nivel_primario');
    }
    
}
