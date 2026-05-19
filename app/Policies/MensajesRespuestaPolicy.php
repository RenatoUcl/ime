<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MensajesRespuesta;

class MensajesRespuestaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, MensajesRespuesta $respuesta): bool
    {
        return $user->hasRole('admin') || $user->id === $respuesta->id_usuario;
    }

    public function create(User $user): bool
    {
        return $user->estado === 1;
    }

    public function update(User $user, MensajesRespuesta $respuesta): bool
    {
        return $user->hasRole('admin') || $user->id === $respuesta->id_usuario;
    }

    public function delete(User $user, MensajesRespuesta $respuesta): bool
    {
        return $user->hasRole('admin');
    }
}
