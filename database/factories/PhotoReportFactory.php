<?php

namespace Database\Factories;

use App\Models\AdvertisingObject;
use App\Models\PhotoReport;
use App\Models\PhotoReportStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PhotoReport>
 */
class PhotoReportFactory extends Factory
{
    protected $model = PhotoReport::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $createdAt = fake()->dateTimeBetween('-2 months', 'now');

        $status = PhotoReportStatus::query()->inRandomOrder()->firstOrFail();

        $checkedBy = null;
        $checkedAt = null;
        $reviewComment = null;

        switch ($status->name) {
            case 'Одобрен':
                $checkedBy = User::query()->inRandomOrder()->value('id');
                $checkedAt = fake()->dateTimeBetween($createdAt, 'now');
                break;

            case 'Отклонен':
                $checkedBy = User::query()->inRandomOrder()->value('id');
                $checkedAt = fake()->dateTimeBetween($createdAt, 'now');
                $reviewComment = fake()->sentence();
                break;

            case 'На проверке':
            default:
                break;
        }

        return [
            'advertising_object_id' => AdvertisingObject::query()->inRandomOrder()->value('id'),
            'created_by' => User::query()->inRandomOrder()->value('id'),
            'photo_report_status_id' => $status->id,

            'comment' => fake()->optional()->sentence(),

            'checked_by' => $checkedBy,
            'checked_at' => $checkedAt,
            'review_comment' => $reviewComment,

            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ];
    }
}