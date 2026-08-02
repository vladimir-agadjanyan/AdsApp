<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Администратор',
                'description' => 'Полный доступ к системе',
            ],
            [
                'name' => 'Менеджер',
                'description' => 'Работа с договорами и рекламными объектами',
            ],
            [
                'name' => 'Проверяющий',
                'description' => 'Проверка фотоотчетов и договоров',
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['name' => $role['name']],
                ['description' => $role['description']]
            );
        }
    }
}