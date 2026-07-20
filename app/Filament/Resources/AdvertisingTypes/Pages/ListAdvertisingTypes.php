<?php

namespace App\Filament\Resources\AdvertisingTypes\Pages;

use App\Filament\Resources\AdvertisingTypes\AdvertisingTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAdvertisingTypes extends ListRecords
{
    protected static string $resource = AdvertisingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
