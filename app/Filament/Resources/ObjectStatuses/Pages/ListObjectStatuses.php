<?php

namespace App\Filament\Resources\ObjectStatuses\Pages;

use App\Filament\Resources\ObjectStatuses\ObjectStatusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListObjectStatuses extends ListRecords
{
    protected static string $resource = ObjectStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
