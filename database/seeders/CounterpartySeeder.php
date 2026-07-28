<?php

namespace Database\Seeders;

use App\Models\Counterparty;
use Illuminate\Database\Seeder;

class CounterpartySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Counterparty::factory()
            ->count(20)
            ->create();
    }
}
