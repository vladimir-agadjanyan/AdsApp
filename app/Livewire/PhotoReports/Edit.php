<?php

namespace App\Livewire\PhotoReports;

use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\City;
use App\Models\Photo;
use App\Models\PhotoReport;
use App\Models\Region;
use App\Services\PhotoReportService;
use App\DTO\PhotoReports\UpdatePhotoReportData;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DomainException;

class Edit extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public PhotoReport $photoReport;

    public ?int $regionId = null;
    public ?int $cityId = null;
    public ?int $advertisingTypeId = null;
    public ?int $advertisingObjectId = null;

    public ?string $comment = null;

    /**
     * @var array<int, mixed>
     */
    public array $photos = [];

    public function mount(PhotoReport $photoReport, PhotoReportService $photoReportService): void
    {
        $this->authorize('update', $photoReport);

        $this->photoReport = $photoReport->load([
            'advertisingObject.city.region',
            'advertisingObject.advertisingType',
            'photoReportStatus',
            'photos',
        ]);

        if (! $photoReportService->canEdit($this->photoReport)) {
            session()->flash(
                'error',
                'Одобренный фотоотчет нельзя редактировать.'
            );

            $this->redirectRoute(
                'photo-reports.show',
                $this->photoReport,
                navigate: true
            );

            return;
        }

        $this->advertisingObjectId = $this->photoReport->advertising_object_id;
        $this->regionId = $this->photoReport->advertisingObject->city->region_id;
        $this->cityId = $this->photoReport->advertisingObject->city_id;
        $this->advertisingTypeId = $this->photoReport->advertisingObject->advertising_type_id;
        $this->comment = $this->photoReport->comment;
    }

    public function updatedRegionId(): void
    {
        $this->cityId = null;
        $this->advertisingObjectId = null;
    }

    public function updatedCityId(): void
    {
        $this->advertisingObjectId = null;
    }

    public function updatedAdvertisingTypeId(): void
    {
        $this->advertisingObjectId = null;
    }

    public function save(PhotoReportService $photoReportService): void
    {
        $this->authorize('update', $this->photoReport);

        $validated = $this->validate([
            'advertisingObjectId' => [
                'required',
                'integer',
                'exists:advertising_objects,id',
            ],
            'comment' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'photos' => [
                'array',
            ],
            'photos.*' => [
                'image',
                'max:10240',
            ],
        ]);

        $data = new UpdatePhotoReportData(
            advertisingObjectId: (int) $validated['advertisingObjectId'],
            comment: $validated['comment'] ?? null,
        );

        try {
            $photoReportService->update($this->photoReport, $data, $validated['photos'] ?? [],);
        } catch (DomainException $e) {
            session()->flash(
                'error',
                $e->getMessage()
            );

            return;
        }

        session()->flash('success', 'Фотоотчет успешно обновлен.');

        $this->redirectRoute('photo-reports.show', $this->photoReport, navigate: true);
    }

    public function deletePhoto(
        Photo $photo,
        PhotoReportService $photoReportService
    ): void {
        abort_unless(
            $photo->photo_report_id === $this->photoReport->id,
            404
        );

        try {
            $photoReportService->deletePhoto(
                $this->photoReport,
                $photo
            );
        } catch (DomainException $e) {
            session()->flash('error', $e->getMessage());

            return;
        }

        $this->photoReport->load('photos');

        session()->flash(
            'success',
            'Фотография успешно удалена.'
        );
    }

    public function render()
    {
        return view('livewire.photo-reports.edit', [
            'regions' => Region::query()
                ->orderBy('name')
                ->get(),

            'cities' => City::query()
                ->when(
                    $this->regionId,
                    fn ($query) => $query->where('region_id', $this->regionId)
                )
                ->orderBy('name')
                ->get(),

            'advertisingTypes' => AdvertisingType::query()
                ->orderBy('name')
                ->get(),

            'advertisingObjects' => AdvertisingObject::query()
                ->with('city')
                ->when(
                    $this->regionId,
                    fn ($query) => $query->whereHas(
                        'city',
                        fn ($city) => $city->where(
                            'region_id',
                            $this->regionId
                        )
                    )
                )
                ->when(
                    $this->cityId,
                    fn ($query) => $query->where(
                        'city_id',
                        $this->cityId
                    )
                )
                ->when(
                    $this->advertisingTypeId,
                    fn ($query) => $query->where(
                        'advertising_type_id',
                        $this->advertisingTypeId
                    )
                )
                ->orderBy('name')
                ->get(),
        ]);
    }
}