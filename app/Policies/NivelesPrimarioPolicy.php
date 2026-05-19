<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NivelesPrimario;

class NivelesPrimarioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, NivelesPrimario $nivel): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, NivelesPrimario $nivel): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, NivelesPrimario $nivel): bool
    {
        return $user->hasRole('admin');
    }
}
