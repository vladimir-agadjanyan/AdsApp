<?php

namespace App\Filament\Resources\Contracts\Tables;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ContractsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('number')
                    ->label('№ договора')
                    ->searchable()
                    ->sortable()
                    ->url(fn ($record) => route(
                        'filament.admin.resources.contracts.edit',
                        $record
                    )),

                TextColumn::make('counterparty.name')
                    ->label('Контрагент')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('contract_date')
                    ->label('Дата договора')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('start_date')
                    ->label('Начало')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('end_date')
                    ->label('Окончание')
                    ->date('d.m.Y')
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
            ->defaultSort('end_date')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                //
            ]);
    }
}