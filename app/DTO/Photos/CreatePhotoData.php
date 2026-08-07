<?php

namespace App\DTO\Photos;

final readonly class CreatePhotoData
{
    public function __construct(
        public int $photoReportId,
        public string $originalName,
        public string $filePath,
        public ?string $mimeType,
        public int $fileSize,
        public int $sortOrder,
    ) {}
}
