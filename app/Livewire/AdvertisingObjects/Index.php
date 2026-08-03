<?php

namespace App\Livewire\AdvertisingObjects;

use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\City;
use App\Models\Counterparty;
use App\Models\ObjectStatus;
use App\Models\Region;
use App\Services\AdvertisingObjectService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use DomainException;


use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public string $search = '';

    public ?int $advertisingTypeId = null;
    public ?int $counterpartyId = null;
    public ?int $objectStatusId = null;
    public ?int $regionId = null;
    public ?int $cityId = null;
    public bool $showDeleteModal = false;

    public ?AdvertisingObject $advertisingObjectToDelete = null;

    public function mount(): void
    {
        $this->authorize('viewAny', AdvertisingObject::class);
    }

    public function updatedRegionId(): void
    {
        $this->cityId = null;
        $this->resetPage();
    }

    public function updating(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'search',
            'advertisingTypeId',
            'counterpartyId',
            'objectStatusId',
            'regionId',
            'cityId',
        ]);

        $this->resetPage();
    }

    public function confirmDelete(AdvertisingObject $advertisingObject): void
    {
        $this->authorize('delete', $advertisingObject);

        $this->advertisingObjectToDelete = $advertisingObject;
        $this->showDeleteModal = true;
    }

    public function cancelDelete(): void
    {
        $this->showDeleteModal = false;
        $this->advertisingObjectToDelete = null;
    }

    public function delete(AdvertisingObjectService $service): void
    {
        $this->authorize('delete', $this->advertisingObjectToDelete);

        if (! $this->advertisingObjectToDelete) {
            return;
        }

        try {$service->delete($this->advertisingObjectToDelete);

            session()->flash(
                'success',
                'Рекламный объект успешно удален.'
            );
        } catch (DomainException $e) {
            session()->flash(
                'error',
                $e->getMessage()
            );
        }

        $this->cancelDelete();

        $this->resetPage();
    }


    public function render()
    {
        $advertisingObjects = AdvertisingObject::query()
            ->with([
                'contract.counterparty',
                'city.region',
                'advertisingType',
                'objectStatus',
            ])
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('name', 'like', "{$this->search}%") // начало названия объекта
                        ->orWhere('address', 'like', "%{$this->search}%") // часть адреса
                        ->orWhereHas('contract', function ($contract) {
                            $contract->where('number', 'like', "{$this->search}%") // начало номера договора
                                ->orWhereHas('counterparty', function ($counterparty) {
                                    $counterparty->where('name', 'like', "%{$this->search}%"); // часть названия контрагента
                                });
                        });
                });
            })
            ->when($this->advertisingTypeId, function ($query) {
                $query->where('advertising_type_id', $this->advertisingTypeId);
            })
            ->when($this->objectStatusId, function ($query) {
                $query->where('object_status_id', $this->objectStatusId);
            })
            ->when($this->cityId, function ($query) {
                $query->where('city_id', $this->cityId);
            })
            ->when($this->regionId, function ($query) {
                $query->whereHas('city', function ($city) {
                    $city->where('region_id', $this->regionId);
                });
            })
            ->when($this->counterpartyId, function ($query) {
                $query->whereHas('contract', function ($contract) {
                    $contract->where('counterparty_id', $this->counterpartyId);
                });
            })
            ->latest()
            ->paginate(10);



        return view('livewire.advertising-objects.index', [
            'advertisingObjects' => $advertisingObjects,

            'advertisingTypes' => AdvertisingType::query()
                ->orderBy('name')
                ->get(),

            'counterparties' => Counterparty::query()
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
                    fn ($query) => $query->where('region_id', $this->regionId)
                )
                ->orderBy('name')
                ->get(),
        ]);
    }
}