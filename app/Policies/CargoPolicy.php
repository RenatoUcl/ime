<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cargos;

class CargoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Cargos $cargo): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Cargos $cargo): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Cargos $cargo): bool
    {
        return $user->hasRole('admin');
    }
}
