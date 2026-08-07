<?php

namespace App\Livewire\Reports;

use App\Exports\SummaryReportExport;
use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\Contract;
use App\Models\Counterparty;
use App\Models\PhotoReport;
use App\Models\PhotoReportStatus;
use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class Summary extends Component
{
    public ?int $regionId = null;

    public ?int $counterpartyId = null;

    public function resetFilters(): void
    {
        $this->reset([
            'regionId',
            'counterpartyId',
        ]);
    }

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

    public function exportExcel(): BinaryFileResponse
    {
        return Excel::download(
            new SummaryReportExport(
                regionId: $this->regionId,
                counterpartyId: $this->counterpartyId,
            ),
            'summary-report-'.now()->format('Y-m-d').'.xlsx'
        );
    }

    public function render(): View
    {
        $contractsQuery = $this->contractsQuery();
        $objectsQuery = $this->advertisingObjectsQuery();
        $photoReportsQuery = $this->photoReportsQuery();
        $contractsCount = (clone $contractsQuery)->count();

        $contractsAmount = (clone $contractsQuery)
            ->with('addendums')
            ->get()
            ->sum(fn (Contract $contract): float => $contract->total_amount);

        $advertisingObjectsCount = (clone $objectsQuery)->count();
        $photoReportsCount = (clone $photoReportsQuery)->count();

        /*
        |----------------------------------------------------------------------
        | Договоры по статусам
        |----------------------------------------------------------------------
        */

        $contractsByStatus = [
            'active' => (clone $contractsQuery)
                ->active()
                ->count(),

            'expiring' => (clone $contractsQuery)
                ->expiring()
                ->count(),

            'expired' => (clone $contractsQuery)
                ->expired()
                ->count(),
        ];

        /*
        |----------------------------------------------------------------------
        | Объекты по регионам
        |----------------------------------------------------------------------
        */
        $objectsByRegion = Region::query()
            ->withCount([
                'advertisingObjects' => function (
                    Builder $query
                ): void {
                    if ($this->counterpartyId) {
                        $query->whereHas(
                            'contract',
                            fn (Builder $contract) => $contract->where(
                                'counterparty_id',
                                $this->counterpartyId
                            )
                        );
                    }
                },
            ])
            ->when(
                $this->regionId,
                fn (Builder $query) => $query->whereKey(
                    $this->regionId
                )
            )
            ->orderBy('name')
            ->get();

        /*
        |----------------------------------------------------------------------
        | Объекты по типам рекламы
        |----------------------------------------------------------------------
        */

        $objectsByType = AdvertisingType::query()
            ->withCount([
                'advertisingObjects' => function (
                    Builder $query
                ): void {
                    if ($this->regionId) {
                        $query->whereHas(
                            'city',
                            fn (Builder $city) => $city->where(
                                'region_id',
                                $this->regionId
                            )
                        );
                    }

                    if ($this->counterpartyId) {
                        $query->whereHas(
                            'contract',
                            fn (Builder $contract) => $contract->where(
                                'counterparty_id',
                                $this->counterpartyId
                            )
                        );
                    }
                },
            ])
            ->orderBy('name')
            ->get();

        /*
        |----------------------------------------------------------------------
        | Фотоотчеты по статусам
        |----------------------------------------------------------------------
        */

        $photoReportsByStatus = PhotoReportStatus::query()
            ->withCount([
                'photoReports' => function (
                    Builder $query
                ): void {
                    if ($this->regionId) {
                        $query->whereHas(
                            'advertisingObject.city',
                            fn (Builder $city) => $city->where(
                                'region_id',
                                $this->regionId
                            )
                        );
                    }

                    if ($this->counterpartyId) {
                        $query->whereHas(
                            'advertisingObject.contract',
                            fn (Builder $contract) => $contract->where(
                                'counterparty_id',
                                $this->counterpartyId
                            )
                        );
                    }
                },
            ])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('livewire.reports.summary', [
            'contractsCount' => $contractsCount,
            'contractsAmount' => $contractsAmount,
            'advertisingObjectsCount' => $advertisingObjectsCount,
            'photoReportsCount' => $photoReportsCount,

            'contractsByStatus' => $contractsByStatus,
            'objectsByRegion' => $objectsByRegion,
            'objectsByType' => $objectsByType,
            'photoReportsByStatus' => $photoReportsByStatus,

            'regions' => Region::query()
                ->orderBy('name')
                ->get(),

            'counterparties' => Counterparty::query()
                ->orderBy('name')
                ->get(),
        ])->layout('layouts.app');
    }
}
