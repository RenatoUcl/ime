<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\Auditable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nombre', 
        'ap_paterno', 
        'ap_materno', 
        'email', 
        'password',
        'telefono'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles()
    {
        return $this->belongsToMany(Roles::class, 'roles_usuarios', 'id_user', 'id_rol');
    }

    /**
     * Verifica si el usuario tiene un rol
     */
    public function hasRole($role)
    {
        return $this->roles()->where('estado', 1)->get()->contains('nombre', $role);
    }


    /**
     * Verifica si el usuario tiene un permiso
     */
    public function hasPermiso($nombrePermiso)
    {
        foreach ($this->roles()->where('estado', 1)->get() as $rol) {
            if ($rol->permisos()->where('estado', 1)->get()->contains('nombre', $nombrePermiso)) {
                return true;
            }
        }
        return false;
    }
}
