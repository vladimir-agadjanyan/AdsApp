<?php

namespace App\DTO\PhotoReports;

final readonly class CreatePhotoReportData
{
    public function __construct(public int $advertisingObjectId, public int $createdBy, public ?string $comment = null) {}

}
