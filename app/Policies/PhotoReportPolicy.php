<?php

namespace App\Policies;

use App\Models\Contract;
use App\Models\User;

class ContractPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin()
            || $user->isManager()
            || $user->isSecurity();
    }

    public function view(User $user, Contract $contract): bool
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

    public function update(User $user, Contract $contract): bool
    {
        return $user->isAdmin()
            || $user->isManager();
    }

    public function delete(User $user, Contract $contract): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, Contract $contract): bool
    {
        return false;
    }

    public function forceDelete(User $user, Contract $contract): bool
    {
        return false;
    }
}