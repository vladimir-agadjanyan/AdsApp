<?php

namespace App\Livewire\PhotoReports;

use App\Models\PhotoReport;
use App\Services\PhotoReportService;
use Livewire\Component;

class Show extends Component
{
    public PhotoReport $photoReport;
    public bool $canEdit = false;

    public function mount(
        PhotoReport $photoReport,
        PhotoReportService $photoReportService
    ): void {
        $this->photoReport = $photoReport->load([
            'advertisingObject.contract.counterparty',
            'advertisingObject.city.region',
            'advertisingObject.advertisingType',
            'photoReportStatus',
            'createdBy',
            'photos',
        ]);

        $this->canEdit = $photoReportService->canEdit($this->photoReport);
    }

    public function render()
    {
        return view('livewire.photo-reports.show');
    }
}