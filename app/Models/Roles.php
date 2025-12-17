<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Roles
 *
 * @property int $id
 * @property string $nombre
 * @property string|null $descripcion
 * @property int $estado
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property \Illuminate\Database\Eloquent\Collection|User[] $users
 * @property \Illuminate\Database\Eloquent\Collection|Permiso[] $permisos
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

    public function permisos()
    {
        return $this->belongsToMany(
            Permiso::class,        // Modelo relacionado
            'permisos_roles',      // Tabla pivote
            'id_rol',              // FK de este modelo
            'id_permiso'           // FK del modelo relacionado
        );
    }

}
