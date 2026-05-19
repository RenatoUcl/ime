<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NivelesSecundario;

class NivelesSecundarioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, NivelesSecundario $nivel): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, NivelesSecundario $nivel): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, NivelesSecundario $nivel): bool
    {
        return $user->hasRole('admin');
    }
}
