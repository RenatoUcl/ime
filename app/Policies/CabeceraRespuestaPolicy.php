<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CabeceraRespuesta;

class CabeceraRespuestaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, CabeceraRespuesta $cabecera): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, CabeceraRespuesta $cabecera): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, CabeceraRespuesta $cabecera): bool
    {
        return $user->hasRole('admin');
    }
}
