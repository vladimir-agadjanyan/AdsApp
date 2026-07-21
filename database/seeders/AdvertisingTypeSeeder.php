<?php

namespace Database\Seeders;

use App\Models\AdvertisingType;
use Illuminate\Database\Seeder;

class AdvertisingTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $advertisingTypes = [
            [
                'name' => 'Билборд',
                'description' => 'Отдельно стоящий рекламный щит большого формата',
            ],
            [
                'name' => 'Ситилайт',
                'description' => 'Рекламная конструкция с внутренней подсветкой',
            ],
            [
                'name' => 'Брандмауэр',
                'description' => 'Рекламная конструкция, размещенная на фасаде здания',
            ],
            [
                'name' => 'LED-экран',
                'description' => 'Светодиодный экран для показа цифровой рекламы',
            ],
            [
                'name' => 'Призматрон',
                'description' => 'Реклама на призматроне',
            ],
            [
                'name' => 'Баннер',
                'description' => 'Реклама на баннере',
            ],
            [
                'name' => 'Пиллар',
                'description' => 'Реклама на пилларе',
            ],

        ];

        foreach ($advertisingTypes as $type) {
            AdvertisingType::create($type);
        }
    }
}
