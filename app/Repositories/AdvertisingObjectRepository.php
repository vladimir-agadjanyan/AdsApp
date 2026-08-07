<?php

namespace App\Repositories;

use App\DTO\AdvertisingObjects\CreateAdvertisingObjectData;
use App\DTO\AdvertisingObjects\UpdateAdvertisingObjectData;
use App\Models\AdvertisingObject;
use Illuminate\Database\Eloquent\Collection;

class AdvertisingObjectRepository
{
    public function __construct(private readonly AdvertisingObject $advertisingObject) {}

    public function create(CreateAdvertisingObjectData $data): AdvertisingObject
    {
        return $this->advertisingObject->create([
            'name' => $data->name,
            'contract_id' => $data->contractId,
            'advertising_type_id' => $data->advertisingTypeId,
            'city_id' => $data->cityId,
            'address' => $data->address,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'object_status_id' => $data->objectStatusId,
            'created_by' => $data->createdBy,
            'note' => $data->note,
        ]);
    }

    public function update(AdvertisingObject $advertisingObject, UpdateAdvertisingObjectData $data): AdvertisingObject
    {
        $advertisingObject->update([
            'name' => $data->name,
            'contract_id' => $data->contractId,
            'advertising_type_id' => $data->advertisingTypeId,
            'city_id' => $data->cityId,
            'address' => $data->address,
            'latitude' => $data->latitude,
            'longitude' => $data->longitude,
            'object_status_id' => $data->objectStatusId,
            'note' => $data->note,
        ]);

        return $advertisingObject;
    }

    /**
     * @return Collection<int, AdvertisingObject>
     */
    public function getWithoutTodayPhotoReport(): Collection
    {
        return $this->advertisingObject
            ->newQuery()
            ->whereHas(
                'objectStatus',
                fn ($query) => $query->where(
                    'name',
                    'Активный'
                )
            )
            ->whereDoesntHave(
                'photoReports',
                fn ($query) => $query->whereDate(
                    'created_at',
                    today()
                )
            )
            ->get();
    }

    public function find(int $id): AdvertisingObject
    {
        return $this->advertisingObject
            ->newQuery()
            ->findOrFail($id);
    }

    public function delete(AdvertisingObject $advertisingObject): void
    {
        $advertisingObject->delete();
    }
}
