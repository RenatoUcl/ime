<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Alternativa;

class AlternativaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Alternativa $alternativa): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Alternativa $alternativa): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Alternativa $alternativa): bool
    {
        return $user->hasRole('admin');
    }
}
