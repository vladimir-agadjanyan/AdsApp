<?php

namespace App\Services;

use App\Models\AdvertisingObject;
use DomainException;


class AdvertisingObjectService
{
    public function create(array $data): AdvertisingObject
    {
        return AdvertisingObject::create($data);
    }

    public function update(AdvertisingObject $advertisingObject, array $data): AdvertisingObject
    {
        $advertisingObject->update($data);

        return $advertisingObject->refresh();
    }

    public function delete(AdvertisingObject $advertisingObject): void
    {
        if ($advertisingObject->photoReports()->exists()) {
            throw new DomainException(
                'Невозможно удалить рекламный объект, так как по нему существуют фотоотчеты.'
            );
        }

        $advertisingObject->delete();
    }
}