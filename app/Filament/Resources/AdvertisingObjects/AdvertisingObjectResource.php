<?php

namespace App\Filament\Resources\AdvertisingObjects;

use App\Filament\Resources\AdvertisingObjects\Pages\CreateAdvertisingObject;
use App\Filament\Resources\AdvertisingObjects\Pages\EditAdvertisingObject;
use App\Filament\Resources\AdvertisingObjects\Pages\ListAdvertisingObjects;
use App\Filament\Resources\AdvertisingObjects\Schemas\AdvertisingObjectForm;
use App\Filament\Resources\AdvertisingObjects\Tables\AdvertisingObjectsTable;
use App\Models\AdvertisingObject;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdvertisingObjectResource extends Resource
{
    protected static ?string $model = AdvertisingObject::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'Рекламные объекты';
    protected static ?string $modelLabel = 'Рекламный объект';
    protected static ?string $pluralModelLabel = 'Рекламные объекты';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return AdvertisingObjectForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdvertisingObjectsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdvertisingObjects::route('/'),
            'create' => CreateAdvertisingObject::route('/create'),
            'edit' => EditAdvertisingObject::route('/{record}/edit'),
        ];
    }
}
