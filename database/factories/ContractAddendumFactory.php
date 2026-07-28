<?php

namespace Database\Factories;

use App\Models\Contract;
use App\Models\ContractAddendum;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContractAddendum>
 */
class ContractAddendumFactory extends Factory
{
    protected $model = ContractAddendum::class;

    public function definition(): array
    {
        $contract = Contract::query()->inRandomOrder()->firstOrFail();

        $signedAt = fake()->dateTimeBetween(
            $contract->contract_date,
            $contract->end_date,
        );

        $amountChange = fake()->boolean(30)
            ? -fake()->numberBetween(1_000_000, 20_000_000)
            : fake()->numberBetween(1_000_000, 20_000_000);

        return [
            'contract_id' => $contract->id,
            'number' => 'ДС-'.fake()->unique()->numerify('#####'),
            'signed_at' => $signedAt,
            'end_date' => fake()->dateTimeBetween($signedAt, '+1 year'),
            'amount_change' => $amountChange,
            'note' => fake()->optional()->sentence(),
            'created_by' => User::query()->inRandomOrder()->value('id'),
        ];
    }
}
