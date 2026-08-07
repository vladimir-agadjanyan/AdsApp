<?php

namespace App\Policies;

use App\Models\AdvertisingObject;
use App\Models\User;

class AdvertisingObjectPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin()
            || $user->isManager()
            || $user->isSecurity();
    }

    public function view(User $user, AdvertisingObject $advertisingObject): bool
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

    public function update(User $user, AdvertisingObject $advertisingObject): bool
    {
        return $user->isAdmin()
            || $user->isManager();
    }

    public function delete(User $user, AdvertisingObject $advertisingObject): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, AdvertisingObject $advertisingObject): bool
    {
        return false;
    }

    public function forceDelete(User $user, AdvertisingObject $advertisingObject): bool
    {
        return false;
    }
}
