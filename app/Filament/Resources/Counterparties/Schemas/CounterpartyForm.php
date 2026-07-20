<?php

namespace App\Filament\Resources\Counterparties\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;

class CounterpartyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema

            ->components([
                TextInput::make('name')
                    ->label('Название организации')
                    ->required()
                    ->maxLength(255),
                TextInput::make('inn')
                    ->label('ИНН')
                    ->required()
                    ->maxLength(20),
                TextInput::make('phone')
                    ->label('Телефон')
                    ->placeholder('+998 90 123-45-67')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                TextInput::make('email')
                    ->label('E-mail')
                    ->email()
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Адрес')
                    ->maxLength(500),
                TextInput::make('contact_person')
                    ->label('Контактное лицо')
                    ->maxLength(255),
                Textarea::make('note')
                    ->label('Примечание')
                    ->rows(4)
                    ->columnSpanFull(),
            ]);
    }
}
