<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LineasProgramaticas;

class LineasProgramaticasPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, LineasProgramaticas $linea): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, LineasProgramaticas $linea): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, LineasProgramaticas $linea): bool
    {
        return $user->hasRole('admin');
    }
}
