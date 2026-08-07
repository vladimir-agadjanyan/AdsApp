<?php

namespace App\Repositories;

use App\DTO\Notifications\CreateNotificationData;
use App\Models\Notification;

class NotificationRepository
{
    public function __construct(private readonly Notification $notification) {}

    public function create(CreateNotificationData $data): Notification
    {
        return $this->notification->create([
            'user_id' => $data->userId,
            'advertising_object_id' => $data->advertisingObjectId,
            'photo_report_id' => $data->photoReportId,
            'title' => $data->title,
            'message' => $data->message,
        ]);
    }
}
