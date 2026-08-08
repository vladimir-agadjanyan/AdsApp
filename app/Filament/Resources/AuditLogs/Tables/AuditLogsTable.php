<?php

namespace App\Filament\Resources\AuditLogs\Tables;

use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\City;
use App\Models\Contract;
use App\Models\Counterparty;
use App\Models\ObjectStatus;
use App\Models\PhotoReport;
use App\Models\PhotoReportStatus;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AuditLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Дата и время')
                    ->dateTime('d.m.Y H:i:s')
                    ->sortable(),

                TextColumn::make('user.name')
                    ->label('Пользователь')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('action')
                    ->label('Действие')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'created' => 'Создан',
                            'updated' => 'Изменен',
                            'deleted' => 'Удален',
                            default => $state,
                        },
                    )
                    ->searchable(),

                TextColumn::make('entity_type')
                    ->label('Тип объекта')
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            Contract::class => 'Договор',
                            AdvertisingObject::class => 'Рекламный объект',
                            PhotoReport::class => 'Фотоотчет',
                            default => $state,
                        },
                    )
                    ->searchable(),

                TextColumn::make('entity_id')
                    ->label('ID объекта')
                    ->sortable(),

                TextColumn::make('description')
                    ->label('Описание')
                    ->searchable()
                    ->limit(80),
            ])
            ->filters([])
            ->recordActions([
                ViewAction::make()
                    ->label('Просмотр')
                    ->modalHeading('Детали действия')
                    ->infolist([
                        TextEntry::make('created_at')
                            ->label('Дата и время')
                            ->dateTime('d.m.Y H:i:s'),

                        TextEntry::make('user.name')
                            ->label('Пользователь'),

                        TextEntry::make('action')
                            ->label('Действие')
                            ->formatStateUsing(
                                fn (string $state): string => match ($state) {
                                    'created' => 'Создан',
                                    'updated' => 'Изменен',
                                    'deleted' => 'Удален',
                                    default => $state,
                                },
                            )
                            ->badge(),

                        TextEntry::make('entity_type')
                            ->label('Тип объекта')
                            ->formatStateUsing(
                                fn (?string $state): string => match ($state) {
                                    Contract::class => 'Договор',
                                    AdvertisingObject::class => 'Рекламный объект',
                                    PhotoReport::class => 'Фотоотчет',
                                    default => $state ?? '—',
                                },
                            ),

                        TextEntry::make('entity_id')
                            ->label('ID объекта'),

                        TextEntry::make('description')
                            ->label('Описание')
                            ->columnSpanFull(),

                        TextEntry::make('old_values')
                            ->label('Было')
                            ->formatStateUsing(
                                fn ($state, $record): string => self::formatValues(
                                    $state,
                                    $record->entity_type,
                                ),
                            )
                            ->html()
                            ->columnSpanFull(),

                        TextEntry::make('new_values')
                            ->label('Стало')
                            ->formatStateUsing(
                                fn ($state, $record): string => self::formatValues(
                                    $state,
                                    $record->entity_type,
                                ),
                            )
                            ->html()
                            ->columnSpanFull(),
                    ]),
            ])
            ->toolbarActions([]);
    }

    private static function formatValues( mixed $values, string $entityType): string
    {
        if (empty($values)) {
            return '<span class="text-gray-500">Нет данных</span>';
        }

        $values = is_string($values)
            ? json_decode($values, true)
            : $values;

        if (! is_array($values)) {
            return '<span class="text-gray-500">Нет данных</span>';
        }

        $labels = match ($entityType) {
            Contract::class => [
                'number' => 'Номер договора',
                'counterparty_id' => 'Контрагент',
                'contract_date' => 'Дата подписания',
                'start_date' => 'Дата начала',
                'end_date' => 'Дата окончания',
                'amount' => 'Сумма',
                'note' => 'Примечание',
            ],

            AdvertisingObject::class => [
                'name' => 'Название',
                'contract_id' => 'Договор',
                'advertising_type_id' => 'Тип рекламы',
                'city_id' => 'Город',
                'address' => 'Адрес',
                'latitude' => 'Широта',
                'longitude' => 'Долгота',
                'object_status_id' => 'Статус',
                'note' => 'Примечание',
            ],

            PhotoReport::class => [
                'advertising_object_id' => 'Рекламный объект',
                'photo_report_status_id' => 'Статус',
                'created_by' => 'Создал',
                'comment' => 'Комментарий',
                'checked_by' => 'Проверил',
                'checked_at' => 'Дата проверки',
                'review_comment' => 'Комментарий проверки',
            ],

            default => [],
        };

        $result = '<div class="space-y-1">';

        foreach ($values as $key => $value) {
            $label = $labels[$key] ?? $key;

            $value = self::resolveValue(
                $key,
                $value,
                $entityType,
            );

            if ($value === null || $value === '') {
                $value = '—';
            }

            $result .= sprintf(
                '<div><strong>%s:</strong> %s</div>',
                e($label),
                e((string) $value),
            );
        }

        $result .= '</div>';

        return $result;
    }

    private static function resolveValue(string $key, mixed $value,  string $entityType): mixed
    {
        if ($value === null) {
            return null;
        }

        return match ($key) {
            'counterparty_id' => self::getModelValue(
                Counterparty::class,
                $value,
                'name',
            ),

            'contract_id' => self::getModelValue(
                Contract::class,
                $value,
                'number',
            ),

            'city_id' => self::getModelValue(
                City::class,
                $value,
                'name',
            ),

            'advertising_type_id' => self::getModelValue(
                AdvertisingType::class,
                $value,
                'name',
            ),

            'object_status_id' => self::getModelValue(
                ObjectStatus::class,
                $value,
                'name',
            ),

            'advertising_object_id' => self::getModelValue(
                AdvertisingObject::class,
                $value,
                'name',
            ),

            'photo_report_status_id' => self::getModelValue(
                PhotoReportStatus::class,
                $value,
                'name',
            ),

            'created_by', 'checked_by' => self::getModelValue(
                User::class,
                $value,
                'name',
            ),

            default => $value,
        };
    }

    private static function getModelValue(string $model, mixed $id, string $field): mixed
    {
        $record = $model::query()->find($id);

        if ($record === null) {
            return $id;
        }

        return $record->{$field};
    }
}