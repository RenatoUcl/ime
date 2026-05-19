<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Permiso;

class PermisoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Permiso $permiso): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Permiso $permiso): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Permiso $permiso): bool
    {
        return $user->hasRole('admin');
    }
}
