<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Администратор',
                'email' => 'admin@adsapp.com',
                'role' => 'Администратор',
            ],
            [
                'name' => 'Менеджер',
                'email' => 'manager@adsapp.com',
                'role' => 'Менеджер',
            ],
            [
                'name' => 'Проверяющий',
                'email' => 'inspector@adsapp.com',
                'role' => 'Проверяющий',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                    'role_id' => Role::query()
                        ->where('name', $user['role'])
                        ->value('id'),
                ]
            );
        }
    }
}
