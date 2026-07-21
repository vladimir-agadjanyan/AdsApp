<?php

namespace App\Filament\Resources\Counterparties;

use App\Filament\Resources\Counterparties\Pages\CreateCounterparty;
use App\Filament\Resources\Counterparties\Pages\EditCounterparty;
use App\Filament\Resources\Counterparties\Pages\ListCounterparties;
use App\Filament\Resources\Counterparties\Schemas\CounterpartyForm;
use App\Filament\Resources\Counterparties\Tables\CounterpartiesTable;
use App\Models\Counterparty;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CounterpartyResource extends Resource
{
    protected static ?string $model = Counterparty::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Контрагенты';

    protected static ?string $modelLabel = 'Контрагента';

    protected static ?string $pluralModelLabel = 'Контрагенты';

    protected static string|UnitEnum|null $navigationGroup = 'Справочники';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CounterpartyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CounterpartiesTable::configure($table);
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
            'index' => ListCounterparties::route('/'),
            'create' => CreateCounterparty::route('/create'),
            'edit' => EditCounterparty::route('/{record}/edit'),
        ];
    }
}
