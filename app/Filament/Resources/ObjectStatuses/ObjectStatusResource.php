<?php

namespace App\Filament\Resources\ObjectStatuses;

use App\Filament\Resources\ObjectStatuses\Pages\CreateObjectStatus;
use App\Filament\Resources\ObjectStatuses\Pages\EditObjectStatus;
use App\Filament\Resources\ObjectStatuses\Pages\ListObjectStatuses;
use App\Filament\Resources\ObjectStatuses\Schemas\ObjectStatusForm;
use App\Filament\Resources\ObjectStatuses\Tables\ObjectStatusesTable;
use App\Models\ObjectStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ObjectStatusResource extends Resource
{
    protected static ?string $model = ObjectStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Статусы объектов';

    protected static ?string $modelLabel = 'Статус объекта';

    protected static ?string $pluralModelLabel = 'Статусы объектов';

    protected static string|UnitEnum|null $navigationGroup = 'Справочники';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return ObjectStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ObjectStatusesTable::configure($table);
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
            'index' => ListObjectStatuses::route('/'),
            'create' => CreateObjectStatus::route('/create'),
            'edit' => EditObjectStatus::route('/{record}/edit'),
        ];
    }
}
