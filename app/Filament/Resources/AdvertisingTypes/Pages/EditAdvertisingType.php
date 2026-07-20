<?php

namespace App\Filament\Resources\AdvertisingTypes\Pages;

use App\Filament\Resources\AdvertisingTypes\AdvertisingTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditAdvertisingType extends EditRecord
{
    protected static string $resource = AdvertisingTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
