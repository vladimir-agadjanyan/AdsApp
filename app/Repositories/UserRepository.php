<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository
{
    public function __construct(private readonly User $user)
    {
    }

    /**
     * @return Collection<int, User>
     */
    public function findByRoleName(string $roleName): Collection
    {
        return $this->user
            ->newQuery()
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