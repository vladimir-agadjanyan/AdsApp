<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Администратор')->first();

        User::firstOrCreate(
            [
                'email' => 'admin@adsapp.local',
            ],
            [
                'name' => 'Администратор',
                'password' => Hash::make('Admin123!'),
                'role_id' => $adminRole->id,
            ]
        );
    }
}
