<?php

namespace App\Filament\Resources\AdvertisingObjects\Pages;

use App\Filament\Resources\AdvertisingObjects\AdvertisingObjectResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAdvertisingObject extends CreateRecord
{
    protected static string $resource = AdvertisingObjectResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = Auth::id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
