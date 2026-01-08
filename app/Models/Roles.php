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

    public function users()
    {
        return $this->belongsToMany(User::class, 'roles_usuarios', 'id_rol', 'id_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Permiso::class,        // Modelo relacionado
            'permisos_roles',      // Tabla pivote
            'id_rol',              // FK de este modelo
            'id_permiso'           // FK del modelo relacionado
        );
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Permiso::class,
            'permisos_roles',
            'id_rol',
            'id_permiso'
        );
    }

}
