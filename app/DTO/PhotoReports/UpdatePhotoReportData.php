<?php

namespace App\DTO\PhotoReports;

final readonly class UpdatePhotoReportData
{
    public function __construct(public int $advertisingObjectId,  public ?string $comment = null)
    {
    }
}