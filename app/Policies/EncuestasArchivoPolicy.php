<?php

namespace App\Policies;

use App\Models\User;
use App\Models\EncuestasArchivo;

class EncuestasArchivoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, EncuestasArchivo $archivo): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, EncuestasArchivo $archivo): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, EncuestasArchivo $archivo): bool
    {
        return $user->hasRole('admin');
    }
}
