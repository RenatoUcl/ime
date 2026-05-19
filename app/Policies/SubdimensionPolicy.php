<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Subdimension;

class SubdimensionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Subdimension $subdimension): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Subdimension $subdimension): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Subdimension $subdimension): bool
    {
        return $user->hasRole('admin');
    }
}
