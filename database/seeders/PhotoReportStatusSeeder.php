<?php

namespace Database\Seeders;

use App\Models\PhotoReportStatus;
use Illuminate\Database\Seeder;

class PhotoReportStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            [
                'name' => 'На проверке',
                'color' => 'warning',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Одобрен',
                'color' => 'success',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Отклонен',
                'color' => 'danger',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($statuses as $status) {
            PhotoReportStatus::updateOrCreate(
                ['name' => $status['name']],
                $status,
            );
        }
    }
}
