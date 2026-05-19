<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Respuesta;

class RespuestaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Respuesta $respuesta): bool
    {
        return $user->hasRole('admin') || $user->id === $respuesta->id_usuario;
    }

    public function create(User $user): bool
    {
        return $user->estado === 1;
    }

    public function update(User $user, Respuesta $respuesta): bool
    {
        return $user->hasRole('admin') || $user->id === $respuesta->id_usuario;
    }

    public function delete(User $user, Respuesta $respuesta): bool
    {
        return $user->hasRole('admin');
    }
}
