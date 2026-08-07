<?php

namespace App\Livewire\Reports;

use App\Exports\AdvertisingObjectsReportExport;
use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\City;
use App\Models\Counterparty;
use App\Models\ObjectStatus;
use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdvertisingObjects extends Component
{
    public ?int $regionId = null;

    public ?int $cityId = null;

    public ?int $counterpartyId = null;

    public ?int $advertisingTypeId = null;

    public ?int $objectStatusId = null;

    public function updatedRegionId(): void
    {
        $this->cityId = null;
    }

    public function resetFilters(): void
    {
        $this->reset([
            'regionId',
            'cityId',
            'counterpartyId',
            'advertisingTypeId',
            'objectStatusId',
        ]);
    }

    /**
     * @return Builder<AdvertisingObject>
     */
    private function objectsQuery(): Builder
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

    public function exportExcel(): BinaryFileResponse
    {
        return Excel::download(
            new AdvertisingObjectsReportExport(
                regionId: $this->regionId,
                cityId: $this->cityId,
                counterpartyId: $this->counterpartyId,
                advertisingTypeId: $this->advertisingTypeId,
                objectStatusId: $this->objectStatusId,
            ),
            'advertising-objects-report-'.
                now()->format('Y-m-d').
                '.xlsx'
        );
    }

    public function render(): View
    {
        return view('livewire.reports.advertising-objects', [
            'advertisingObjects' => $this->objectsQuery()->get(),

            'regions' => Region::query()
                ->orderBy('name')
                ->get(),

            'cities' => City::query()
                ->when(
                    $this->regionId,
                    fn (Builder $query) => $query->where(
                        'region_id',
                        $this->regionId
                    )
                )
                ->orderBy('name')
                ->get(),

            'counterparties' => Counterparty::query()
                ->orderBy('name')
                ->get(),

            'advertisingTypes' => AdvertisingType::query()
                ->orderBy('name')
                ->get(),

            'objectStatuses' => ObjectStatus::query()
                ->orderBy('name')
                ->get(),
        ])->layout('layouts.app');
    }
}
