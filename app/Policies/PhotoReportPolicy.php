<?php

namespace App\Policies;

use App\Models\PhotoReport;
use App\Models\User;

class PhotoReportPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isSecurity();
    }

    public function view(User $user, PhotoReport $photoReport): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isSecurity();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    public function update(User $user, PhotoReport $photoReport): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    public function review(User $user, PhotoReport $photoReport): bool
    {
        return $user->isAdmin() || $user->isSecurity();
    }

    public function delete(User $user, PhotoReport $photoReport): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, PhotoReport $photoReport): bool
    {
        return false;
    }

    public function forceDelete(User $user, PhotoReport $photoReport): bool
    {
        return false;
    }
}
