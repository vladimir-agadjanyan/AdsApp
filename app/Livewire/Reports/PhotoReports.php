<?php

namespace App\Livewire\Reports;

use App\Models\City;
use App\Models\Counterparty;
use App\Models\PhotoReport;
use App\Models\PhotoReportStatus;
use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use App\Exports\PhotoReportsReportExport;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PhotoReports extends Component
{
    public ?int $regionId = null;
    public ?int $cityId = null;
    public ?int $counterpartyId = null;
    public ?int $photoReportStatusId = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;

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
            'photoReportStatusId',
            'dateFrom',
            'dateTo',
        ]);
    }

    /**
     * @return Builder<PhotoReport>
     */
    private function photoReportsQuery(): Builder
    {
        return PhotoReport::query()
            ->with([
                'advertisingObject.contract.counterparty',
                'advertisingObject.city.region',
                'photoReportStatus',
                'createdBy',
                'checkedBy',
            ])
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
                $this->cityId,
                fn (Builder $query) => $query->whereHas(
                    'advertisingObject',
                    fn (Builder $object) => $object->where(
                        'city_id',
                        $this->cityId
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
            )
            ->when(
                $this->photoReportStatusId,
                fn (Builder $query) => $query->where(
                    'photo_report_status_id',
                    $this->photoReportStatusId
                )
            )
            ->when(
                $this->dateFrom,
                fn (Builder $query) => $query->whereDate(
                    'created_at',
                    '>=',
                    $this->dateFrom
                )
            )
            ->when(
                $this->dateTo,
                fn (Builder $query) => $query->whereDate(
                    'created_at',
                    '<=',
                    $this->dateTo
                )
            )
            ->latest('created_at');
    }
    
    public function exportExcel(): BinaryFileResponse
    {
        return Excel::download(
            new PhotoReportsReportExport(
                regionId: $this->regionId,
                cityId: $this->cityId,
                counterpartyId: $this->counterpartyId,
                photoReportStatusId: $this->photoReportStatusId,
                dateFrom: $this->dateFrom,
                dateTo: $this->dateTo,
            ),
            'photo-reports-report-' .
                now()->format('Y-m-d') .
                '.xlsx'
        );
    }

    public function render(): View
    {
        return view('livewire.reports.photo-reports', [
            'photoReports' => $this->photoReportsQuery()->get(),

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

            'photoReportStatuses' => PhotoReportStatus::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get(),
        ])->layout('layouts.app');
    }
}