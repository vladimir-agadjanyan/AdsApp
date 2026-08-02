<?php

namespace App\Livewire\PhotoReports;

use App\Models\PhotoReport;
use App\Services\PhotoReportService;
use DomainException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public PhotoReport $photoReport;

    public bool $canEdit = false;

    public ?string $reviewComment = null;

    public function mount(
        PhotoReport $photoReport,
        PhotoReportService $photoReportService
    ): void {
        $this->authorize('view', $photoReport);

        $this->photoReport = $photoReport->load([
            'advertisingObject.contract.counterparty',
            'advertisingObject.city.region',
            'advertisingObject.advertisingType',
            'photoReportStatus',
            'createdBy',
            'checkedBy',
            'photos',
        ]);

        $this->canEdit = $photoReportService->canEdit(
            $this->photoReport
        );

        $this->reviewComment = $this->photoReport->review_comment;
    }

    public function approve(
        PhotoReportService $photoReportService
    ): void {
        $this->authorize(
            'review',
            $this->photoReport
        );

        $this->validate([
            'reviewComment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        try {
            $photoReportService->approve(
                $this->photoReport,
                (int) auth()->id(),
                $this->reviewComment
            );
        } catch (DomainException $e) {
            session()->flash(
                'error',
                $e->getMessage()
            );

            return;
        }

        $this->refreshPhotoReport(
            $photoReportService
        );

        session()->flash(
            'success',
            'Фотоотчет успешно одобрен.'
        );
    }

    public function reject(
        PhotoReportService $photoReportService
    ): void {
        $this->authorize(
            'review',
            $this->photoReport
        );

        $this->validate([
            'reviewComment' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'reviewComment.required' =>
                'Укажите причину отклонения фотоотчета.',
        ]);

        try {
            $photoReportService->reject(
                $this->photoReport,
                (int) auth()->id(),
                $this->reviewComment
            );
        } catch (DomainException $e) {
            session()->flash(
                'error',
                $e->getMessage()
            );

            return;
        }

        $this->refreshPhotoReport(
            $photoReportService
        );

        session()->flash(
            'success',
            'Фотоотчет отклонен.'
        );
    }

    private function refreshPhotoReport(
        PhotoReportService $photoReportService
    ): void {
        $this->photoReport->refresh();

        $this->photoReport->load([
            'advertisingObject.contract.counterparty',
            'advertisingObject.city.region',
            'advertisingObject.advertisingType',
            'photoReportStatus',
            'createdBy',
            'checkedBy',
            'photos',
        ]);

        $this->canEdit = $photoReportService->canEdit(
            $this->photoReport
        );

        $this->reviewComment = $this->photoReport->review_comment;
    }

    public function render()
    {
        return view('livewire.photo-reports.show');
    }
}