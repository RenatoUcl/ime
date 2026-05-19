<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Archivo;

class ArchivoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Archivo $archivo): bool
    {
        return $user->hasRole('admin') || $user->id === $archivo->id_usuario;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->estado === 1;
    }

    public function update(User $user, Archivo $archivo): bool
    {
        return $user->hasRole('admin') || $user->id === $archivo->id_usuario;
    }

    public function delete(User $user, Archivo $archivo): bool
    {
        return $user->hasRole('admin');
    }
}
