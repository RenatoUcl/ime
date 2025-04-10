<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permiso
 *
 * @property $id
 * @property $nombre
 * @property $descripcion
 * @property $estado
 * @property $created_at
 * @property $updated_at
 *
 * @property PermisosRole[] $permisosRoles
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Permiso extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['nombre', 'descripcion', 'estado'];


    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'permisos_roles', 'id_permiso', 'id_rol');
    }
    
}
