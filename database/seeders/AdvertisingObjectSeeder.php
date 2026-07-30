<?php

namespace Database\Seeders;

use App\Models\AdvertisingObject;
use Illuminate\Database\Seeder;

class AdvertisingObjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AdvertisingObject::factory()
            ->count(200)
            ->create();
    }
}