<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MensajesArchivo;

class MensajesArchivoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, MensajesArchivo $archivo): bool
    {
        return $user->hasRole('admin') || $user->id === $archivo->id_usuario;
    }

    public function create(User $user): bool
    {
        return $user->estado === 1;
    }

    public function update(User $user, MensajesArchivo $archivo): bool
    {
        return $user->hasRole('admin') || $user->id === $archivo->id_usuario;
    }

    public function delete(User $user, MensajesArchivo $archivo): bool
    {
        return $user->hasRole('admin');
    }
}
