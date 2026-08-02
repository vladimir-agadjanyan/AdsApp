<?php

namespace App\Policies;

use App\Models\Counterparty;
use App\Models\User;

class CounterpartyPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin()
            || $user->isManager()
            || $user->isSecurity();
    }

    public function view(User $user, Counterparty $counterparty): bool
    {
        return $user->isAdmin()
            || $user->isManager()
            || $user->isSecurity();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin()
            || $user->isManager();
    }

    public function update(User $user, Counterparty $counterparty): bool
    {
        return $user->isAdmin()
            || $user->isManager();
    }

    public function delete(User $user, Counterparty $counterparty): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Counterparty $counterparty): bool
    {
        return false;
    }

    public function forceDelete(User $user, Counterparty $counterparty): bool
    {
        return false;
    }
}