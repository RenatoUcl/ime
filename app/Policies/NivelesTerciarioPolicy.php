<?php

namespace App\Policies;

use App\Models\User;
use App\Models\NivelesTerciario;

class NivelesTerciarioPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, NivelesTerciario $nivel): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, NivelesTerciario $nivel): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, NivelesTerciario $nivel): bool
    {
        return $user->hasRole('admin');
    }
}
