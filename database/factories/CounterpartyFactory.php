<?php

namespace Database\Factories;

use App\Models\Counterparty;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Counterparty>
 */
class CounterpartyFactory extends Factory
{
    protected $model = Counterparty::class;

    public function definition(): array
    {
        return [
            'name' => fake()->unique()->company(),
            'inn' => fake()->unique()->numerify('#########'),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'contact_person' => fake()->name(),
            'note' => fake()->optional()->sentence(),
        ];
    }
}
