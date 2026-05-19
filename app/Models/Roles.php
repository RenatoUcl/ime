<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Auditable;

class Roles extends Model
{
    use SoftDeletes, Auditable;

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
