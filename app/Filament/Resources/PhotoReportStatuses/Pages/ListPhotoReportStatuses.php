<?php

namespace App\Filament\Resources\PhotoReportStatuses\Pages;

use App\Filament\Resources\PhotoReportStatuses\PhotoReportStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhotoReportStatuses extends ListRecords
{
    protected static string $resource = PhotoReportStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
