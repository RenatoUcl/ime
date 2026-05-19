<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CabeceraPregunta;

class CabeceraPreguntaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, CabeceraPregunta $cabecera): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, CabeceraPregunta $cabecera): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, CabeceraPregunta $cabecera): bool
    {
        return $user->hasRole('admin');
    }
}
