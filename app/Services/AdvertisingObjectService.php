<?php

namespace App\Services;

use App\DTO\AdvertisingObjects\CreateAdvertisingObjectData;
use App\DTO\AdvertisingObjects\UpdateAdvertisingObjectData;
use App\DTO\AuditLog\CreateAuditLogData;
use App\Models\AdvertisingObject;
use App\Repositories\AdvertisingObjectRepository;
use Illuminate\Support\Facades\Auth;
use DomainException;

class AdvertisingObjectService
{
    public function __construct(
        private readonly AdvertisingObjectRepository $advertisingObjectRepository,
        private readonly AuditLogService $auditLogService,
    ) {
    }

    public function create(CreateAdvertisingObjectData $data): AdvertisingObject
    {
        $advertisingObject = $this->advertisingObjectRepository->create($data);

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'created',
                entityType: AdvertisingObject::class,
                entityId: $advertisingObject->id,
                description: "Создан рекламный объект «{$advertisingObject->name}»",
                oldValues: null,
                newValues: [
                    'name' => $advertisingObject->name,
                    'contract_id' => $advertisingObject->contract_id,
                    'advertising_type_id' => $advertisingObject->advertising_type_id,
                    'city_id' => $advertisingObject->city_id,
                    'address' => $advertisingObject->address,
                    'latitude' => $advertisingObject->latitude,
                    'longitude' => $advertisingObject->longitude,
                    'object_status_id' => $advertisingObject->object_status_id,
                    'note' => $advertisingObject->note,
                ],
            ),
        );

        return $advertisingObject;
    }

    public function update(AdvertisingObject $advertisingObject, UpdateAdvertisingObjectData $data): AdvertisingObject
    {
        $oldValues = [
            'name' => $advertisingObject->name,
            'contract_id' => $advertisingObject->contract_id,
            'advertising_type_id' => $advertisingObject->advertising_type_id,
            'city_id' => $advertisingObject->city_id,
            'address' => $advertisingObject->address,
            'latitude' => $advertisingObject->latitude,
            'longitude' => $advertisingObject->longitude,
            'object_status_id' => $advertisingObject->object_status_id,
            'note' => $advertisingObject->note,
        ];

        $advertisingObject = $this->advertisingObjectRepository->update(
            $advertisingObject,
            $data,
        );

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'updated',
                entityType: AdvertisingObject::class,
                entityId: $advertisingObject->id,
                description: "Изменен рекламный объект «{$advertisingObject->name}»",
                oldValues: $oldValues,
                newValues: [
                    'name' => $advertisingObject->name,
                    'contract_id' => $advertisingObject->contract_id,
                    'advertising_type_id' => $advertisingObject->advertising_type_id,
                    'city_id' => $advertisingObject->city_id,
                    'address' => $advertisingObject->address,
                    'latitude' => $advertisingObject->latitude,
                    'longitude' => $advertisingObject->longitude,
                    'object_status_id' => $advertisingObject->object_status_id,
                    'note' => $advertisingObject->note,
                ],
            ),
        );

        return $advertisingObject;
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
                'Невозможно удалить рекламный объект, так как по нему существуют фотоотчеты.',
            );
        }

        $objectName = $advertisingObject->name;

        $oldValues = [
            'name' => $advertisingObject->name,
            'contract_id' => $advertisingObject->contract_id,
            'advertising_type_id' => $advertisingObject->advertising_type_id,
            'city_id' => $advertisingObject->city_id,
            'address' => $advertisingObject->address,
            'latitude' => $advertisingObject->latitude,
            'longitude' => $advertisingObject->longitude,
            'object_status_id' => $advertisingObject->object_status_id,
            'note' => $advertisingObject->note,
        ];

        $this->advertisingObjectRepository->delete($advertisingObject);

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'deleted',
                entityType: AdvertisingObject::class,
                entityId: $advertisingObject->id,
                description: "Удален рекламный объект «{$objectName}»",
                oldValues: $oldValues,
                newValues: null,
            ),
        );
    }
}