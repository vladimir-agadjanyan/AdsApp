<?php

namespace App\Livewire\PhotoReports;

use App\Models\AdvertisingType;
use App\Models\City;
use App\Models\PhotoReport;
use App\Models\PhotoReportStatus;
use App\Models\Region;
use App\Services\PhotoReportService;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Index extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public string $search = '';
    public ?int $regionId = null;
    public ?int $cityId = null;
    public ?int $photoReportStatusId = null;
    public ?string $dateFrom = null;
    public ?string $dateTo = null;
    public ?int $advertisingTypeId = null;
    public bool $showDeleteModal = false;
    public ?PhotoReport $photoReportToDelete = null;

    public function resetFilters(): void
    {
        $this->reset([
            'search',
            'regionId',
            'cityId',
            'photoReportStatusId',
            'advertisingTypeId',
            'dateFrom',
            'dateTo',
        ]);

        $this->resetPage();
    }

    public function updated(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(PhotoReport $photoReport): void
    {
        $this->authorize('delete', $photoReport);

        $this->photoReportToDelete = $photoReport->load([
            'advertisingObject',
            'photos',
        ]);

        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->photoReportToDelete = null;
    }

    public function delete(PhotoReportService $photoReportService): void
    {
        if (! $this->photoReportToDelete) {
            return;
        }

        $this->authorize(
            'delete',
            $this->photoReportToDelete
        );

        $photoReportService->delete(
            $this->photoReportToDelete
        );

        $this->cancelDelete();

        session()->flash(
            'success',
            'Фотоотчет успешно удален.'
        );

        $this->resetPage();
    }

    public function render()
    {
        $photoReports = PhotoReport::query()
            ->with([
                'advertisingObject.city.region',
                'photoReportStatus',
            ])
            ->withCount('photos')

            ->when(
                $this->search,
                fn ($query) => $query->whereHas(
                    'advertisingObject',
                    fn ($q) => $q->where(
                        'name',
                        'like',
                        "%{$this->search}%"
                    )
                )
            )

            ->when(
                $this->regionId,
                fn ($query) => $query->whereHas(
                    'advertisingObject.city',
                    fn ($q) => $q->where(
                        'region_id',
                        $this->regionId
                    )
                )
            )

            ->when(
                $this->cityId,
                fn ($query) => $query->whereHas(
                    'advertisingObject',
                    fn ($q) => $q->where(
                        'city_id',
                        $this->cityId
                    )
                )
            )

            ->when(
                $this->photoReportStatusId,
                fn ($query) => $query->where(
                    'photo_report_status_id',
                    $this->photoReportStatusId
                )
            )

            ->when(
                $this->dateFrom,
                fn ($query) => $query->whereDate(
                    'created_at',
                    '>=',
                    $this->dateFrom
                )
            )

            ->when(
                $this->dateTo,
                fn ($query) => $query->whereDate(
                    'created_at',
                    '<=',
                    $this->dateTo
                )
            )

            ->when(
                $this->advertisingTypeId,
                function ($query) {
                    $query->whereHas(
                        'advertisingObject',
                        function ($query) {
                            $query->where(
                                'advertising_type_id',
                                $this->advertisingTypeId
                            );
                        }
                    );
                }
            )

            ->latest()
            ->paginate(15);

        return view('livewire.photo-reports.index', [
            'photoReports' => $photoReports,

            'regions' => Region::query()
                ->orderBy('name')
                ->get(),

            'cities' => City::query()
                ->when(
                    $this->regionId,
                    fn ($query) => $query->where(
                        'region_id',
                        $this->regionId
                    )
                )
                ->orderBy('name')
                ->get(),

            'advertisingTypes' => AdvertisingType::query()
                ->orderBy('name')
                ->get(),

            'photoReportStatuses' => PhotoReportStatus::query()
                ->orderBy('name')
                ->get(),
        ]);
    }
}