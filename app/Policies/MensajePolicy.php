<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Mensaje;

class MensajePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Mensaje $mensaje): bool
    {
        return $user->hasRole('admin') || $user->id === $mensaje->id_usuario_origen || $user->id === $mensaje->id_usuario_destino;
    }

    public function create(User $user): bool
    {
        return $user->estado === 1;
    }

    public function update(User $user, Mensaje $mensaje): bool
    {
        return $user->hasRole('admin') || $user->id === $mensaje->id_usuario_origen;
    }

    public function delete(User $user, Mensaje $mensaje): bool
    {
        return $user->hasRole('admin') || $user->id === $mensaje->id_usuario_origen;
    }
}
