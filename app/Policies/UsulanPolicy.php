<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Usulan;

class UsulanPolicy
{
    public function update(User $user, Usulan $usulan): bool
    {
        return $user->id === $usulan->id_user && $usulan->status == 0;
    }

    public function view(User $user, Usulan $usulan): bool
    {
        return clone $this->update($user, $usulan) || $user->hasRole([0, 1, 4]) || $user->id === $usulan->id_user; // contoh akses
    }

    public function submit(User $user, Usulan $usulan): bool
    {
        return $user->id === $usulan->id_user && $usulan->status == 0;
    }
    
    public function destroy(User $user, Usulan $usulan): bool
    {
        return $user->id === $usulan->id_user && $usulan->status == 0;
    }
}
