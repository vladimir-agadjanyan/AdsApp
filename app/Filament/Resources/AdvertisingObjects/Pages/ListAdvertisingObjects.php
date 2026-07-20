<?php

namespace App\Filament\Resources\AdvertisingObjects\Pages;

use App\Filament\Resources\AdvertisingObjects\AdvertisingObjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdvertisingObjects extends ListRecords
{
    protected static string $resource = AdvertisingObjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
