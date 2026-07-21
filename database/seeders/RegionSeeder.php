<?php

namespace Database\Seeders;

use App\Models\Region;
use Illuminate\Database\Seeder;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            'Республика Каракалпакстан',
            'Андижанская область',
            'Бухарская область',
            'Джизакская область',
            'Кашкадарьинская область',
            'Навоийская область',
            'Наманганская область',
            'Самаркандская область',
            'Сырдарьинская область',
            'Сурхандарьинская область',
            'Ташкентская область',
            'Ферганская область',
            'Хорезмская область',
        ];

        foreach ($regions as $region) {
            Region::firstOrCreate([
                'name' => $region,
            ]);
        }
    }
}
