<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
       /**
     * @return Collection<int, User>
     */
    public function findByRoleName(string $roleName): Collection
    {
        return User::query()
            ->whereHas(
                'role',
                fn ($query) => $query->where(
                    'name',
                    $roleName
                )
            )
            ->get();
    }
}