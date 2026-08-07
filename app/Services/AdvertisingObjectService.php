<?php

namespace App\Services;

use App\DTO\AdvertisingObjects\CreateAdvertisingObjectData;
use App\DTO\AdvertisingObjects\UpdateAdvertisingObjectData;
use App\Models\AdvertisingObject;
use App\Repositories\AdvertisingObjectRepository;
use DomainException;

class AdvertisingObjectService
{
    public function __construct(private readonly AdvertisingObjectRepository $advertisingObjectRepository) {}

    public function create(CreateAdvertisingObjectData $data): AdvertisingObject
    {
        return $this->advertisingObjectRepository->create($data);
    }

    public function update(AdvertisingObject $advertisingObject, UpdateAdvertisingObjectData $data): AdvertisingObject
    {
        return $this->advertisingObjectRepository->update($advertisingObject, $data);
    }

    public function find(int $id): AdvertisingObject
    {
        return $this->advertisingObjectRepository->find($id);
    }

    public function canDelete(AdvertisingObject $advertisingObject): bool
    {
        return ! $advertisingObject->photoReports()->exists();
    }

    public function delete(AdvertisingObject $advertisingObject): void
    {
        if (! $this->canDelete($advertisingObject)) {
            throw new DomainException(
                'Невозможно удалить рекламный объект, так как по нему существуют фотоотчеты.'
            );
        }

        $this->advertisingObjectRepository->delete($advertisingObject);
    }
}
