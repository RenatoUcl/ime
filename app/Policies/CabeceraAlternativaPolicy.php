<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CabeceraAlternativa;

class CabeceraAlternativaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, CabeceraAlternativa $cabecera): bool
    {
        return $user->hasRole('admin');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function update(User $user, CabeceraAlternativa $cabecera): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, CabeceraAlternativa $cabecera): bool
    {
        return $user->hasRole('admin');
    }
}
