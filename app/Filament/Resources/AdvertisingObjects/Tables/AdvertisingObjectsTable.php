<?php

namespace App\Filament\Resources\AdvertisingObjects\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AdvertisingObjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Название')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(fn ($record) => $record->address)
                    ->url(fn ($record) => route(
                        'filament.admin.resources.advertising-objects.edit',
                        $record
                    )),

                TextColumn::make('contract.number')
                    ->label('Договор')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('city.name')
                    ->label('Город')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('advertisingType.name')
                    ->label('Тип рекламы')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('objectStatus.name')
                    ->label('Статус')
                    ->badge()
                    ->color(fn ($record) => $record->objectStatus->color)
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('updated_at')
                    ->label('Изменен')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([
                EditAction::make()
                    ->label('Изменить'),

                DeleteAction::make()
                    ->label('Удалить'),
            ]);
    }
}