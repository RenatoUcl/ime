<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Departamento;

class DepartamentoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Departamento $departamento): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Departamento $departamento): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Departamento $departamento): bool
    {
        return $user->hasRole('admin');
    }
}
