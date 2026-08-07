<?php

namespace App\Repositories;

use App\Models\Notification;

class NotificationRepository
{
    public function create(int $userId, int $advertisingObjectId, int $photoReportId, string $title, string $message): Notification
    {
        return Notification::query()->create([
            'user_id' => $userId,
            'advertising_object_id' => $advertisingObjectId,
            'photo_report_id' => $photoReportId,
            'title' => $title,
            'message' => $message,
        ]);
    }
}