<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Encuesta;

class EncuestaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Encuesta $encuesta): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Encuesta $encuesta): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Encuesta $encuesta): bool
    {
        return $user->hasRole('admin');
    }

    public function clone(User $user, Encuesta $encuesta): bool
    {
        return $user->hasRole('admin');
    }

    public function respond(User $user, Encuesta $encuesta): bool
    {
        return $user->estado === 1 && $encuesta->estado === 1;
    }
}
