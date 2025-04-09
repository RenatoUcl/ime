<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Role
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property NivelesPrimario[] $nivelesPrimarios
 * @property NivelesSecundario[] $nivelesSecundarios
 * @property NivelesTerciario[] $nivelesTerciarios
 * @property PermisosRole[] $permisosRoles
 * @property RolesUsuario[] $rolesUsuarios
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Roles extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'descripcion', 'estado'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nivelesPrimarios()
    {
        return $this->hasMany(\App\Models\NivelesPrimario::class, 'id', 'id_rol');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nivelesSecundarios()
    {
        return $this->hasMany(\App\Models\NivelesSecundario::class, 'id', 'id_rol');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function nivelesTerciarios()
    {
        return $this->hasMany(\App\Models\NivelesTerciario::class, 'id', 'id_rol');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permisosRoles()
    {
        return $this->hasMany(\App\Models\PermisosRole::class, 'id', 'id_rol');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rolesUsuarios()
    {
        return $this->hasMany(\App\Models\RolesUsuario::class, 'id', 'id_user');
    }
    
}
