<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

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
        return $this->roles->contains('nombre', $role);
    }

    /**
     * Verifica si el usuario tiene un permiso
     */
    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->permiso->contains('nombre', $permission)) {
                return true;
            }
        }
        return false;
    }
}
