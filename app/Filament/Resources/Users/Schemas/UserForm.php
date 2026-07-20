<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            TextInput::make('name')
                ->label('Имя')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

          Select::make('role_id')
                ->label('Роль')
                ->relationship('role', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('password')
                ->label('Пароль')
                ->password()
                ->revealable()
                ->required(fn(string $operation) => $operation === 'create')
                ->dehydrated(fn($state) => filled($state))
                ->maxLength(255),
        ]);
    }
}
