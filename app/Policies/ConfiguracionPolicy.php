<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Configuracion;

class ConfiguracionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Configuracion $configuracion): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Configuracion $configuracion): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Configuracion $configuracion): bool
    {
        return $user->hasRole('admin');
    }
}
