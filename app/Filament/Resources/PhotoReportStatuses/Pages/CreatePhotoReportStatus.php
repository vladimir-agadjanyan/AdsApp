<?php

namespace App\Filament\Resources\PhotoReportStatuses\Pages;

use App\Filament\Resources\PhotoReportStatuses\PhotoReportStatusResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePhotoReportStatus extends CreateRecord
{
    protected static string $resource = PhotoReportStatusResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
