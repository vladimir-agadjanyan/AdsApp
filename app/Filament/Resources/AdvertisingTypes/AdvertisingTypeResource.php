<?php

namespace App\Filament\Resources\AdvertisingTypes;

use App\Models\AdvertisingType;

use App\Filament\Resources\AdvertisingTypes\Pages\CreateAdvertisingType;
use App\Filament\Resources\AdvertisingTypes\Pages\EditAdvertisingType;
use App\Filament\Resources\AdvertisingTypes\Pages\ListAdvertisingTypes;
use App\Filament\Resources\AdvertisingTypes\Schemas\AdvertisingTypeForm;
use App\Filament\Resources\AdvertisingTypes\Tables\AdvertisingTypesTable;

use BackedEnum;
use UnitEnum;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AdvertisingTypeResource extends Resource
{
    protected static ?string $model = AdvertisingType::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Типы рекламы';
    protected static ?string $modelLabel = 'Тип рекламы';
    protected static ?string $pluralModelLabel = 'Типы рекламы';
    protected static string|UnitEnum|null $navigationGroup = 'Справочники';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        return AdvertisingTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AdvertisingTypesTable::configure($table);
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
            'index' => ListAdvertisingTypes::route('/'),
            'create' => CreateAdvertisingType::route('/create'),
            'edit' => EditAdvertisingType::route('/{record}/edit'),
        ];
    }
}
