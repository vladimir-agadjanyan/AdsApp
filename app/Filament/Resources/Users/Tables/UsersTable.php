<?php

namespace App\Filament\Resources\Users\Tables;

use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Имя')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('role.name')
                    ->label('Роль')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Создан')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),

                DeleteAction::make()
                    ->before(function (
                        DeleteAction $action,
                        User $record
                    ): void {
                        /*
                        |----------------------------------------------------------------------
                        | Нельзя удалить самого себя
                        |----------------------------------------------------------------------
                        */

                        if (auth()->id() === $record->id) {
                            Notification::make()
                                ->danger()
                                ->title('Удаление невозможно')
                                ->body(
                                    'Вы не можете удалить свою учетную запись.'
                                )
                                ->send();

                            $action->halt();

                            return;
                        }

                        /*
                        |----------------------------------------------------------------------
                        | Нельзя удалить автора рекламных объектов
                        |----------------------------------------------------------------------
                        */

                        if ($record->advertisingObjects()->exists()) {
                            Notification::make()
                                ->danger()
                                ->title('Удаление невозможно')
                                ->body(
                                    'Пользователь связан с рекламными объектами и не может быть удален.'
                                )
                                ->send();

                            $action->halt();
                        }
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function (
                            DeleteBulkAction $action,
                            Collection $records
                        ): void {
                            /*
                            |------------------------------------------------------------------
                            | Нельзя удалить самого себя
                            |------------------------------------------------------------------
                            */

                            if ($records->contains('id', auth()->id())) {
                                Notification::make()
                                    ->danger()
                                    ->title('Удаление невозможно')
                                    ->body(
                                        'В выбранных пользователях находится ваша учетная запись.'
                                    )
                                    ->send();

                                $action->halt();

                                return;
                            }

                            /*
                            |------------------------------------------------------------------
                            | Проверяем связи с рекламными объектами
                            |------------------------------------------------------------------
                            */

                            foreach ($records as $record) {
                                if (
                                    $record instanceof User
                                    && $record->advertisingObjects()->exists()
                                ) {
                                    Notification::make()
                                        ->danger()
                                        ->title('Удаление невозможно')
                                        ->body(
                                            'Один или несколько выбранных пользователей связаны с рекламными объектами.'
                                        )
                                        ->send();

                                    $action->halt();

                                    return;
                                }
                            }
                        }),
                ]),
            ]);
    }
}