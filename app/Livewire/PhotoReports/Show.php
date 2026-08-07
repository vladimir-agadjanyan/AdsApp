<?php

namespace App\Livewire\PhotoReports;

use App\DTO\PhotoReports\ApprovePhotoReportData;
use App\DTO\PhotoReports\RejectPhotoReportData;
use App\Models\PhotoReport;
use App\Services\PhotoReportService;
use DomainException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesRequests;

    public PhotoReport $photoReport;

    public bool $canEdit = false;

    public ?string $reviewComment = null;

    public function mount(PhotoReport $photoReport, PhotoReportService $photoReportService): void
    {
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

        $this->canEdit = $photoReportService->canEdit($this->photoReport);

        $this->reviewComment = $this->photoReport->review_comment;
    }

    public function approve(PhotoReportService $photoReportService): void
    {
        $this->authorize('review', $this->photoReport);

        $this->validate([
            'reviewComment' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);

        try {

            $data = new ApprovePhotoReportData(checkedBy: (int) Auth::id(), reviewComment: $this->reviewComment);
            $photoReportService->approve($this->photoReport, $data);

        } catch (DomainException $e) {
            session()->flash('error', $e->getMessage());

            return;
        }

        session()->flash('success', 'Фотоотчет успешно одобрен.');

        $this->redirectRoute('photo-reports.index', navigate: true);
    }

    public function reject(PhotoReportService $photoReportService): void
    {
        $this->authorize('review', $this->photoReport);

        $this->validate([
            'reviewComment' => [
                'required',
                'string',
                'max:2000',
            ],
        ], [
            'reviewComment.required' => 'Укажите причину отклонения фотоотчета.',
        ]);

        try {

            $data = new RejectPhotoReportData(checkedBy: (int) Auth::id(), reviewComment: $this->reviewComment);
            $photoReportService->reject($this->photoReport, $data);

        } catch (DomainException $e) {
            session()->flash('error', $e->getMessage());

            return;
        }

        session()->flash('success', 'Фотоотчет отклонен.');

        $this->redirectRoute('photo-reports.index', navigate: true);
    }

    public function render()
    {
        return view('livewire.photo-reports.show');
    }
}
