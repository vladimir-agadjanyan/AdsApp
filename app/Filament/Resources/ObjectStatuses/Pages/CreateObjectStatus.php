<?php

namespace App\Filament\Resources\ObjectStatuses\Pages;

use App\Filament\Resources\ObjectStatuses\ObjectStatusResource;
use Filament\Resources\Pages\CreateRecord;

class CreateObjectStatus extends CreateRecord
{
    protected static string $resource = ObjectStatusResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
