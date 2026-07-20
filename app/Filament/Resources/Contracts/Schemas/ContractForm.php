<?php

namespace App\Filament\Resources\Contracts\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ContractForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Select::make('counterparty_id')
                    ->label('Контрагент')
                    ->relationship('counterparty', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                TextInput::make('number')
                    ->label('Номер договора')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),

                DatePicker::make('contract_date')
                    ->label('Дата договора')
                    ->required(),

                DatePicker::make('start_date')
                    ->label('Дата начала')
                    ->required(),

                DatePicker::make('end_date')
                    ->label('Дата окончания')
                    ->required(),

                FileUpload::make('file')
                    ->label('Скан договора')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'image/jpeg',
                        'image/png',
                    ])
                    ->directory('contracts')
                    ->preserveFilenames()
                    ->maxSize(10240)
                    ->downloadable()
                    ->openable(),

                Textarea::make('note')
                    ->label('Примечание')
                    ->rows(4)
                    ->columnSpanFull(),

            ]);
    }
}