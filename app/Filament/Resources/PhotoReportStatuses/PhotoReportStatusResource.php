<?php

namespace App\Filament\Resources\PhotoReportStatuses;

use App\Filament\Resources\PhotoReportStatuses\Pages\CreatePhotoReportStatus;
use App\Filament\Resources\PhotoReportStatuses\Pages\EditPhotoReportStatus;
use App\Filament\Resources\PhotoReportStatuses\Pages\ListPhotoReportStatuses;
use App\Filament\Resources\PhotoReportStatuses\Schemas\PhotoReportStatusForm;
use App\Filament\Resources\PhotoReportStatuses\Tables\PhotoReportStatusesTable;
use App\Models\PhotoReportStatus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class PhotoReportStatusResource extends Resource
{
    protected static ?string $model = PhotoReportStatus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationLabel = 'Статусы фотоотчетов';

    protected static ?string $modelLabel = 'Статус фотоотчета';

    protected static ?string $pluralModelLabel = 'Статусы фотоотчетов';

    protected static string|UnitEnum|null $navigationGroup = 'Справочники';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return PhotoReportStatusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PhotoReportStatusesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPhotoReportStatuses::route('/'),
            'create' => CreatePhotoReportStatus::route('/create'),
            'edit' => EditPhotoReportStatus::route('/{record}/edit'),
        ];
    }
}
