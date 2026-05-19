<?php

namespace App\Policies;

use App\Models\User;
use App\Models\MensajesEstado;

class MensajesEstadoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, MensajesEstado $estado): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, MensajesEstado $estado): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, MensajesEstado $estado): bool
    {
        return $user->hasRole('admin');
    }
}
