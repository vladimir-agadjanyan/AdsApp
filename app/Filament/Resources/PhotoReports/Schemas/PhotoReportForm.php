<?php

namespace App\Filament\Resources\PhotoReports\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PhotoReportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('advertising_object_id')
                    ->relationship('advertisingObject', 'name')
                    ->required(),
                TextInput::make('created_by')
                    ->required()
                    ->numeric(),
                TextInput::make('status_id')
                    ->required()
                    ->numeric(),
                Textarea::make('comment')
                    ->columnSpanFull(),
                TextInput::make('checked_by')
                    ->numeric(),
                DateTimePicker::make('checked_at'),
                Textarea::make('review_comment')
                    ->columnSpanFull(),
            ]);
    }
}
