<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Dimension;

class DimensionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Dimension $dimension): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Dimension $dimension): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Dimension $dimension): bool
    {
        return $user->hasRole('admin');
    }
}
