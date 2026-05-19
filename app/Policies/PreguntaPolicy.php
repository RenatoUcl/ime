<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Pregunta;

class PreguntaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, Pregunta $pregunta): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, Pregunta $pregunta): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, Pregunta $pregunta): bool
    {
        return $user->hasRole('admin');
    }
}
