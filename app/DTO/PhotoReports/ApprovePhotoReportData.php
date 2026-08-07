<?php

namespace App\DTO\PhotoReports;

final readonly class ApprovePhotoReportData
{
    public function __construct(public int $checkedBy, public ?string $reviewComment = null) {}
}
