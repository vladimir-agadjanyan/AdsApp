<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\Counterparty;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    protected $model = Contract::class;

    public function definition(): array
    {
        $contractDate = fake()->dateTimeBetween('-1 year', '-1 month');
        $startDate = (clone $contractDate)->modify('+7 days');
        $endDate = (clone $startDate)->modify('+1 year');

        return [
            'counterparty_id' => Counterparty::factory(),
            'number' => 'Д-'.fake()->unique()->numerify('2026-####'),
            'contract_date' => $contractDate,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'amount' => fake()->numberBetween(10_000_000, 500_000_000),
            'note' => fake()->boolean(30)
                ? fake()->sentence()
                : null,
            'created_by' => User::factory(),
        ];
    }
}
