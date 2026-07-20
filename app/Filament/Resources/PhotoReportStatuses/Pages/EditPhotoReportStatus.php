<?php

namespace App\Filament\Resources\PhotoReportStatuses\Pages;

use App\Filament\Resources\PhotoReportStatuses\PhotoReportStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPhotoReportStatus extends EditRecord
{
    protected static string $resource = PhotoReportStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
