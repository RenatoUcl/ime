<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EncuestasUsuario;

class EncuestasUsuarioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, EncuestasUsuario $encuestaUsuario): bool
    {
        return $user->hasRole('admin') || $user->id === $encuestaUsuario->id_usuario;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, EncuestasUsuario $encuestaUsuario): bool
    {
        return $user->hasRole('admin') || $user->id === $encuestaUsuario->id_usuario;
    }

    public function delete(User $user, EncuestasUsuario $encuestaUsuario): bool
    {
        return $user->hasRole('admin');
    }
}
