<?php

namespace App\Livewire\Map;

use App\Models\AdvertisingObject;
use App\Models\AdvertisingType;
use App\Models\City;
use App\Models\Counterparty;
use App\Models\ObjectStatus;
use App\Models\Region;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Component;

class Index extends Component
{
    public ?int $regionId = null;

    public ?int $cityId = null;

    public ?int $counterpartyId = null;

    public ?int $advertisingTypeId = null;

    public ?int $objectStatusId = null;

    public function updatedRegionId(): void
    {
        $this->cityId = null;

        $this->dispatchMapObjects();
    }

    public function updatedCityId(): void
    {
        $this->dispatchMapObjects();
    }

    public function updatedCounterpartyId(): void
    {
        $this->dispatchMapObjects();
    }

    public function updatedAdvertisingTypeId(): void
    {
        $this->dispatchMapObjects();
    }

    public function updatedObjectStatusId(): void
    {
        $this->dispatchMapObjects();
    }

    public function resetFilters(): void
    {
        $this->reset([
            'regionId',
            'cityId',
            'counterpartyId',
            'advertisingTypeId',
            'objectStatusId',
        ]);

        $this->dispatchMapObjects();
    }

    /**
     * Базовый запрос рекламных объектов с учетом фильтров.
     *
     * @return Builder<AdvertisingObject>
     */
    private function objectsQuery(): Builder
    {
        return AdvertisingObject::query()
            ->with([
                'contract.counterparty',
                'city.region',
                'advertisingType',
                'objectStatus',
            ])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
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
                $this->cityId,
                fn (Builder $query) => $query->where(
                    'city_id',
                    $this->cityId
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
            )
            ->when(
                $this->advertisingTypeId,
                fn (Builder $query) => $query->where(
                    'advertising_type_id',
                    $this->advertisingTypeId
                )
            )
            ->when(
                $this->objectStatusId,
                fn (Builder $query) => $query->where(
                    'object_status_id',
                    $this->objectStatusId
                )
            );
    }

    /**
     * Получает рекламные объекты для карты.
     *
     * @return Collection<int, AdvertisingObject>
     */
    private function getAdvertisingObjects(): Collection
    {
        return $this->objectsQuery()->get();
    }

    /**
     * Отправляет актуальные маркеры в JavaScript
     * после изменения фильтров.
     */
    private function dispatchMapObjects(): void
    {
        $objects = [];

        foreach ($this->getAdvertisingObjects() as $object) {
            $objects[] = [
                'id' => $object->id,
                'name' => $object->name,
                'latitude' => $object->latitude,
                'longitude' => $object->longitude,

                'counterparty' => $object->contract->counterparty->name,

                'region' => $object->city->region->name,

                'city' => $object->city->name,

                'type' => $object->advertisingType->name,

                'status' => $object->objectStatus->name,

                'url' => route(
                    'advertising-objects.show',
                    $object
                ),
            ];
        }

        $this->dispatch(
            'map-objects-updated',
            objects: $objects
        );
    }

    public function render(): View
    {
        return view('livewire.map.index', [
            'advertisingObjects' => $this->getAdvertisingObjects(),

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

            'advertisingTypes' => AdvertisingType::query()
                ->orderBy('name')
                ->get(),

            'objectStatuses' => ObjectStatus::query()
                ->orderBy('name')
                ->get(),
        ]);
    }
}
