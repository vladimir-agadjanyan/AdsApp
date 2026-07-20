<?php

namespace App\Filament\Resources\ObjectStatuses\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ObjectStatusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->label('Название статуса')
                    ->required()
                    ->maxLength(255),

                ColorPicker::make('color')
                    ->label('Цвет')
                    ->required(),

                TextInput::make('sort_order')
                    ->label('Порядок сортировки')
                    ->numeric()
                    ->default(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Активен')
                    ->default(true),
            ]);
    }
}