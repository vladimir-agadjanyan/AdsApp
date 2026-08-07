<?php

namespace App\Livewire\PhotoReports;

use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\City;
use App\Models\Region;
use App\Services\PhotoReportService;
use App\DTO\PhotoReports\CreatePhotoReportData;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use App\Models\PhotoReport;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use AuthorizesRequests;
    use WithFileUploads;

    public ?int $regionId = null;
    public ?int $cityId = null;
    public ?int $advertisingTypeId = null;
    public ?int $advertisingObjectId = null;
    public ?string $comment = null;
    public array $photos = [];

    public function mount(): void
    {
        $this->authorize('create', PhotoReport::class);
    }

    protected function rules(): array
    {
        return [
            'regionId' => ['required', 'exists:regions,id'],
            'cityId' => ['required', 'exists:cities,id'],
            'advertisingTypeId' => ['required', 'exists:advertising_types,id'],
            'advertisingObjectId' => ['required', 'exists:advertising_objects,id'],
            'comment' => ['nullable', 'string', 'max:1000'],

            'photos' => ['required', 'array', 'min:1'],
            'photos.*' => ['image', 'max:5120'],
        ];
    }

    public function updatedRegionId(): void
    {
        $this->cityId = null;
        $this->advertisingTypeId = null;
        $this->advertisingObjectId = null;
    }

    public function updatedCityId(): void
    {
        $this->advertisingTypeId = null;
        $this->advertisingObjectId = null;
    }

    public function updatedAdvertisingTypeId(): void
    {
        $this->advertisingObjectId = null;
    }

    public function save(PhotoReportService $service)
    {
        $this->authorize('create', PhotoReport::class);

        $this->validate();

        $data = new CreatePhotoReportData(
            advertisingObjectId: (int) $this->advertisingObjectId,
            createdBy: (int) Auth::id(),
            comment: $this->comment,
        );

        $service->create($data, $this->photos);

        session()->flash('success', 'Фотоотчет успешно создан.');

        return redirect()->route('photo-reports.index');
    }

    public function render()
    {
        return view('livewire.photo-reports.create', [

            'regions' => Region::query()
                ->orderBy('name')
                ->get(),

            'cities' => City::query()
                ->when(
                    $this->regionId,
                    fn($query) => $query->where('region_id', $this->regionId)
                )
                ->orderBy('name')
                ->get(),

            'advertisingTypes' => AdvertisingType::query()
                ->orderBy('name')
                ->get(),

            'advertisingObjects' => AdvertisingObject::query()
                ->when(
                    $this->cityId,
                    fn($query) => $query->where('city_id', $this->cityId)
                )
                ->when(
                    $this->advertisingTypeId,
                    fn($query) => $query->where('advertising_type_id', $this->advertisingTypeId)
                )
                ->orderBy('name')
                ->get(),

        ]);
    }
}