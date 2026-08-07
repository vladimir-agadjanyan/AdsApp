<?php

namespace App\Livewire\AdvertisingObjects;

use App\DTO\AdvertisingObjects\UpdateAdvertisingObjectData;
use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\City;
use App\Models\Contract;
use App\Models\Counterparty;
use App\Models\ObjectStatus;
use App\Models\Region;
use App\Services\AdvertisingObjectService;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class Edit extends Component
{
    use AuthorizesRequests;

    public AdvertisingObject $advertisingObject;
    public ?int $counterpartyId = null;
    public ?int $contract_id = null;
    public string $name = '';
    public ?int $advertising_type_id = null;
    public ?int $object_status_id = null;
    public ?int $regionId = null;
    public ?int $city_id = null;
    public string $address = '';
    public ?float $latitude = null;
    public ?float $longitude = null;
    public string $note = '';

    public function mount(AdvertisingObject $advertisingObject): void
    {
        $this->authorize('update', $advertisingObject);
        $this->advertisingObject = $advertisingObject;
        $this->counterpartyId = $advertisingObject->contract->counterparty_id;
        $this->contract_id = $advertisingObject->contract_id;
        $this->name = $advertisingObject->name;
        $this->advertising_type_id = $advertisingObject->advertising_type_id;
        $this->object_status_id = $advertisingObject->object_status_id;
        $this->regionId = $advertisingObject->city->region_id;
        $this->city_id = $advertisingObject->city_id;
        $this->address = $advertisingObject->address;
        $this->latitude = $advertisingObject->latitude;
        $this->longitude = $advertisingObject->longitude;
        $this->note = $advertisingObject->note ?? '';
    }

    public function update(AdvertisingObjectService $advertisingObjectService): mixed
    {
        $this->authorize('update', $this->advertisingObject);
        $this->validate();

        $data = new UpdateAdvertisingObjectData(
            name: $this->name,
            contractId: $this->contract_id,
            advertisingTypeId: $this->advertising_type_id,
            cityId: $this->city_id,
            address: $this->address,
            latitude: (float) ($this->latitude ?? 0),
            longitude: (float) ($this->longitude ?? 0),
            objectStatusId: $this->object_status_id,
            note: $this->note ?: null,
        );

        $this->advertisingObject = $advertisingObjectService->update($this->advertisingObject, $data);

        session()->flash('success', 'Рекламный объект успешно обновлен.');

        return redirect()->route('advertising-objects.index');
    }

    protected function rules(): array
    {
        return [
            'contract_id' => [
                'required',
                'exists:contracts,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'advertising_type_id' => [
                'required',
                'exists:advertising_types,id',
            ],

            'object_status_id' => [
                'required',
                'exists:object_statuses,id',
            ],

            'city_id' => [
                'required',
                'exists:cities,id',
            ],

            'address' => [
                'required',
                'string',
                'max:255',
            ],

            'latitude' => [
                'nullable',
                'numeric',
            ],

            'longitude' => [
                'nullable',
                'numeric',
            ],

            'note' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function updatedCounterpartyId(): void
    {
        $this->contract_id = null;
    }

    public function updatedRegionId(): void
    {
        $this->city_id = null;
    }

    public function render(): View
    {
        return view(
            'livewire.advertising-objects.edit',
            [
                'counterparties' => Counterparty::query()
                    ->orderBy('name')
                    ->get(),

                'contracts' => Contract::query()
                    ->when(
                        $this->counterpartyId,
                        function ($query) {
                            $query->where(
                                'counterparty_id',
                                $this->counterpartyId
                            );
                        }
                    )
                    ->orderBy('number')
                    ->get(),

                'advertisingTypes' => AdvertisingType::query()
                    ->orderBy('name')
                    ->get(),

                'objectStatuses' => ObjectStatus::query()
                    ->orderBy('name')
                    ->get(),

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
            ]
        );
    }
}