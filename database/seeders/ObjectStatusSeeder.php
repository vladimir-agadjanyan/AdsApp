<?php

namespace Database\Seeders;

use App\models\ObjectStatus;
use Illuminate\Database\Seeder;

class ObjectStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $objectStatusSeeder = [
            [
                'name' => 'Активный',
                'color' => 'success',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Свободный',
                'color' => 'warning',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Недоступный',
                'color' => 'danger',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($objectStatusSeeder as $status) {
            ObjectStatus::create($status);
        }
    }
}
