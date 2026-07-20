<?php

namespace App\Filament\Resources\AdvertisingObjects\Schemas;

use App\Models\City;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class AdvertisingObjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('contract_id')
                    ->label('Договор')
                    ->relationship('contract', 'number')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('region_id')
                    ->label('Регион')
                    ->relationship('region', 'name')
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('city_id', null))
                    ->required(),

                Select::make('city_id')
                    ->label('Город')
                    ->options(fn (Get $get) => City::query()
                        ->where('region_id', $get('region_id'))
                        ->orderBy('name')
                        ->pluck('name', 'id'))
                    ->searchable()
                    ->required(),

                Select::make('advertising_type_id')
                    ->label('Тип рекламы')
                    ->relationship('advertisingType', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Select::make('object_status_id')
                    ->label('Статус')
                    ->relationship('objectStatus', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('name')
                    ->label('Название объекта')
                    ->required()
                    ->maxLength(255),

                TextInput::make('address')
                    ->label('Адрес')
                    ->required()
                    ->maxLength(255),

                TextInput::make('latitude')
                    ->label('Широта')
                    ->numeric()
                    ->minValue(-90)
                    ->maxValue(90),

                TextInput::make('longitude')
                    ->label('Долгота')
                    ->numeric()
                    ->minValue(-180)
                    ->maxValue(180),

                Textarea::make('note')
                    ->label('Примечание')
                    ->columnSpanFull(),
            ])
            ->columns(1);
    }
}