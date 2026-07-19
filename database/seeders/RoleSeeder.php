<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
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
            Role::create($role);
        }
    }
}
