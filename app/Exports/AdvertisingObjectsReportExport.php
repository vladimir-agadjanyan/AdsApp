<?php

namespace App\Exports;

use App\Models\AdvertisingObject;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithTitle;

/**
 * @implements WithMapping<AdvertisingObject>
 */
class AdvertisingObjectsReportExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithTitle
{
    public function __construct(
        private readonly ?int $regionId = null,
        private readonly ?int $cityId = null,
        private readonly ?int $counterpartyId = null,
        private readonly ?int $advertisingTypeId = null,
        private readonly ?int $objectStatusId = null,
    ) {
    }

    /**
     * @return Builder<AdvertisingObject>
     */
    public function query(): Builder
    {
        return AdvertisingObject::query()
            ->with([
                'contract.counterparty',
                'city.region',
                'advertisingType',
                'objectStatus',
            ])
            ->when(
                $this->regionId,
                fn (Builder $query) => $query->whereHas(
                    'city',
                    fn (Builder $city) => $city->where(
                        'region_id',
                        $this->regionId
                    )
                )
            )
            ->when(
                $this->cityId,
                fn (Builder $query) => $query->where(
                    'city_id',
                    $this->cityId
                )
            )
            ->when(
                $this->counterpartyId,
                fn (Builder $query) => $query->whereHas(
                    'contract',
                    fn (Builder $contract) => $contract->where(
                        'counterparty_id',
                        $this->counterpartyId
                    )
                )
            )
            ->when(
                $this->advertisingTypeId,
                fn (Builder $query) => $query->where(
                    'advertising_type_id',
                    $this->advertisingTypeId
                )
            )
            ->when(
                $this->objectStatusId,
                fn (Builder $query) => $query->where(
                    'object_status_id',
                    $this->objectStatusId
                )
            )
            ->orderBy('name');
    }

    /**
     * @return array<int, string>
     */
    public function headings(): array
    {
        return [
            'Объект',
            'Договор',
            'Контрагент',
            'Регион',
            'Город',
            'Адрес',
            'Тип рекламы',
            'Статус',
            'Широта',
            'Долгота',
        ];
    }

    /**
     * @return array<int, string|float>
     */
    public function map($object): array
    {
        return [
            $object->name,
            $object->contract->number,
            $object->contract->counterparty->name,
            $object->city->region->name,
            $object->city->name,
            $object->address,
            $object->advertisingType->name,
            $object->objectStatus->name,
            $object->latitude ?? '',
            $object->longitude ?? '',
        ];
    }

    public function title(): string
    {
        return 'Рекламные объекты';
    }
}