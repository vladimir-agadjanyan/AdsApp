<?php

namespace Database\Factories;

use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\City;
use App\Models\Contract;
use App\Models\ObjectStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AdvertisingObject>
 */
class AdvertisingObjectFactory extends Factory
{
    protected $model = AdvertisingObject::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $contract = Contract::query()->inRandomOrder(null)->firstOrFail();
        $type = AdvertisingType::query()->inRandomOrder(null)->firstOrFail();
        $city = City::query()->inRandomOrder(null)->firstOrFail();
        $status = ObjectStatus::query()->inRandomOrder(null)->firstOrFail();
        $user = User::query()->inRandomOrder(null)->firstOrFail();
        
        return [
            'name' => fake()->randomElement([
                'LED-экран',
                'Билборд',
                'Ситилайт',
                'Остановка',
                'Брандмауэр',
                'Лайтбокс',
            ]) . ' №' . fake()->numberBetween(1, 999),

            'contract_id' => Contract::query()->inRandomOrder()->value('id'),
            'advertising_type_id' => AdvertisingType::query()->inRandomOrder()->value('id'),
            'city_id' => City::query()->inRandomOrder()->value('id'),
            'address' => fake()->streetAddress(),
            'latitude' => fake()->latitude(37.0, 46.0),
            'longitude' => fake()->longitude(56.0, 73.0),
            'object_status_id' => ObjectStatus::query()->inRandomOrder()->value('id'),
            'created_by' => User::query()->inRandomOrder()->value('id'),
            'note' => fake()->optional()->sentence(),
        ];
    }
}