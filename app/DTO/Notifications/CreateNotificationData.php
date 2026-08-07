<?php

namespace App\DTO\Notifications;

readonly class CreateNotificationData
{
    public function __construct(
        public int $userId,
        public int $advertisingObjectId,
        public int $photoReportId,
        public string $title,
        public string $message,
    ) {}
}
