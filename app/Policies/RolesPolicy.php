<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Roles;

class RolesPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Roles $role): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Roles $role): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Roles $role): bool
    {
        return false;
    }

    public function assignPermisos(User $user, Roles $role): bool
    {
        return $user->hasRole('admin');
    }
}
