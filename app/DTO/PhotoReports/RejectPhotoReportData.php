<?php

namespace App\DTO\PhotoReports;

final readonly class RejectPhotoReportData
{
    public function __construct(public int $checkedBy, public string $reviewComment)
    {
    }
}