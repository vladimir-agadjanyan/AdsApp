<?php

namespace App\Filament\Resources\PhotoReports\Pages;

use App\Filament\Resources\PhotoReports\PhotoReportResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPhotoReports extends ListRecords
{
    protected static string $resource = PhotoReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
