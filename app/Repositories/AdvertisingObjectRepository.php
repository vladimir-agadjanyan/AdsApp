<?php

namespace App\Repositories;

use App\DTO\AdvertisingObjects\CreateAdvertisingObjectData;
use App\DTO\AdvertisingObjects\UpdateAdvertisingObjectData;
use App\Models\AdvertisingObject;

class AdvertisingObjectRepository
{
    public function create(CreateAdvertisingObjectData $data): AdvertisingObject
    {
        return AdvertisingObject::create([
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

    public function find(int $id): AdvertisingObject
    {
        return AdvertisingObject::findOrFail($id);
    }

    public function delete(AdvertisingObject $advertisingObject): void
    {
        $advertisingObject->delete();
    }
}