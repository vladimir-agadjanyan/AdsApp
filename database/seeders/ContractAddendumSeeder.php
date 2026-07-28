<?php

namespace Database\Seeders;

use App\Models\ContractAddendum;
use Illuminate\Database\Seeder;

class ContractAddendumSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ContractAddendum::factory()
            ->count(15)
            ->create();
    }
}
