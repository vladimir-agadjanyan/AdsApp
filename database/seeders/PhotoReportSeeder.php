<?php

namespace Database\Seeders;

use App\Models\PhotoReport;
use Illuminate\Database\Seeder;

class PhotoReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PhotoReport::factory()
            ->count(100)
            ->create();
    }
}
