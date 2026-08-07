<?php

namespace App\Exports;

use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\Contract;
use App\Models\Counterparty;
use App\Models\PhotoReport;
use App\Models\PhotoReportStatus;
use App\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class SummarySheetExport implements FromCollection, ShouldAutoSize, WithTitle
{
    public function __construct(
        private readonly ?int $regionId = null,
        private readonly ?int $counterpartyId = null,
    ) {}

    /**
     * @return Builder<AdvertisingObject>
     */
    private function advertisingObjectsQuery(): Builder
    {
        return AdvertisingObject::query()
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
                $this->counterpartyId,
                fn (Builder $query) => $query->whereHas(
                    'contract',
                    fn (Builder $contract) => $contract->where(
                        'counterparty_id',
                        $this->counterpartyId
                    )
                )
            );
    }

    /**
     * @return Builder<Contract>
     */
    private function contractsQuery(): Builder
    {
        return Contract::query()
            ->when(
                $this->counterpartyId,
                fn (Builder $query) => $query->where(
                    'counterparty_id',
                    $this->counterpartyId
                )
            )
            ->when(
                $this->regionId,
                fn (Builder $query) => $query->whereHas(
                    'advertisingObjects.city',
                    fn (Builder $city) => $city->where(
                        'region_id',
                        $this->regionId
                    )
                )
            );
    }

    /**
     * @return Builder<PhotoReport>
     */
    private function photoReportsQuery(): Builder
    {
        return PhotoReport::query()
            ->when(
                $this->regionId,
                fn (Builder $query) => $query->whereHas(
                    'advertisingObject.city',
                    fn (Builder $city) => $city->where(
                        'region_id',
                        $this->regionId
                    )
                )
            )
            ->when(
                $this->counterpartyId,
                fn (Builder $query) => $query->whereHas(
                    'advertisingObject.contract',
                    fn (Builder $contract) => $contract->where(
                        'counterparty_id',
                        $this->counterpartyId
                    )
                )
            );
    }

    /**
     * @return Collection<int, array{string, string|int|float}>
     */
    public function collection(): Collection
    {
        $contractsQuery = $this->contractsQuery();
        $objectsQuery = $this->advertisingObjectsQuery();
        $photoReportsQuery = $this->photoReportsQuery();

        /*
        |--------------------------------------------------------------------------
        | Основные показатели
        |--------------------------------------------------------------------------
        */

        $contractsCount = (clone $contractsQuery)->count();

        $contractsAmount = (clone $contractsQuery)
            ->with('addendums')
            ->get()
            ->sum(
                fn (Contract $contract): float => $contract->total_amount
            );

        $advertisingObjectsCount = (clone $objectsQuery)->count();

        $photoReportsCount = (clone $photoReportsQuery)->count();

        /*
        |--------------------------------------------------------------------------
        | Договоры по статусам
        |--------------------------------------------------------------------------
        */

        $activeContracts = (clone $contractsQuery)
            ->active()
            ->count();

        $expiringContracts = (clone $contractsQuery)
            ->expiring()
            ->count();

        $expiredContracts = (clone $contractsQuery)
            ->expired()
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Названия выбранных фильтров
        |--------------------------------------------------------------------------
        */

        $regionName = $this->regionId
            ? Region::query()
                ->findOrFail($this->regionId)
                ->name
            : 'Все регионы';

        $counterpartyName = $this->counterpartyId
            ? Counterparty::query()
                ->findOrFail($this->counterpartyId)
                ->name
            : 'Все контрагенты';

        /*
        |--------------------------------------------------------------------------
        | Формируем лист
        |--------------------------------------------------------------------------
        */

        $rows = collect([
            ['СВОДНЫЙ ОТЧЕТ ADSAPP', ''],

            ['Дата формирования', now()->format('d.m.Y H:i')],
            ['Регион', $regionName],
            ['Контрагент', $counterpartyName],

            ['', ''],

            ['ОСНОВНЫЕ ПОКАЗАТЕЛИ', ''],
            ['Договоры', $contractsCount],
            ['Общая сумма договоров', $contractsAmount],
            ['Рекламные объекты', $advertisingObjectsCount],
            ['Фотоотчеты', $photoReportsCount],

            ['', ''],

            ['ДОГОВОРЫ ПО СТАТУСАМ', ''],
            ['Активные', $activeContracts],
            ['Скоро заканчиваются', $expiringContracts],
            ['Просрочены', $expiredContracts],

            ['', ''],

            ['ФОТООТЧЕТЫ ПО СТАТУСАМ', ''],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Фотоотчеты по статусам
        |--------------------------------------------------------------------------
        */

        $photoReportStatuses = PhotoReportStatus::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        foreach ($photoReportStatuses as $status) {
            $count = (clone $photoReportsQuery)
                ->where(
                    'photo_report_status_id',
                    $status->id
                )
                ->count();

            $rows->push([
                $status->name,
                $count,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Объекты по регионам
        |--------------------------------------------------------------------------
        */

        $rows->push(['', '']);
        $rows->push(['ОБЪЕКТЫ ПО РЕГИОНАМ', '']);

        $regions = Region::query()
            ->when(
                $this->regionId,
                fn (Builder $query) => $query->whereKey(
                    $this->regionId
                )
            )
            ->orderBy('name')
            ->get();

        foreach ($regions as $region) {
            $count = (clone $objectsQuery)
                ->whereHas(
                    'city',
                    fn (Builder $city) => $city->where(
                        'region_id',
                        $region->id
                    )
                )
                ->count();

            $rows->push([
                $region->name,
                $count,
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Объекты по типам рекламы
        |--------------------------------------------------------------------------
        */

        $rows->push(['', '']);
        $rows->push(['ОБЪЕКТЫ ПО ТИПАМ РЕКЛАМЫ', '']);

        $advertisingTypes = AdvertisingType::query()
            ->orderBy('name')
            ->get();

        foreach ($advertisingTypes as $type) {
            $count = (clone $objectsQuery)
                ->where(
                    'advertising_type_id',
                    $type->id
                )
                ->count();

            $rows->push([
                $type->name,
                $count,
            ]);
        }

        return $rows;
    }

    public function title(): string
    {
        return 'Сводка';
    }
}
