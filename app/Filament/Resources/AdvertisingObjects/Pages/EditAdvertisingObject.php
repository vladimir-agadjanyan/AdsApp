<?php

namespace App\Filament\Resources\AdvertisingObjects\Pages;

use App\Filament\Resources\AdvertisingObjects\AdvertisingObjectResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdvertisingObject extends EditRecord
{
    protected static string $resource = AdvertisingObjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
