<?php

namespace App\Filament\Resources\PhotoReports\Pages;

use App\Filament\Resources\PhotoReports\PhotoReportResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPhotoReport extends EditRecord
{
    protected static string $resource = PhotoReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
