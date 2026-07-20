<?php

namespace App\Filament\Resources\ObjectStatuses\Pages;

use App\Filament\Resources\ObjectStatuses\ObjectStatusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditObjectStatus extends EditRecord
{
    protected static string $resource = ObjectStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
