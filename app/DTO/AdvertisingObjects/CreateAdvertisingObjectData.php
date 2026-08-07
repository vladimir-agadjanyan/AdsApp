<?php

namespace App\DTO\AdvertisingObjects;

readonly class CreateAdvertisingObjectData
{
    public function __construct(
        public string $name,
        public int $contractId,
        public int $advertisingTypeId,
        public int $cityId,
        public string $address,
        public float $latitude,
        public float $longitude,
        public int $objectStatusId,
        public int $createdBy,
        public ?string $note,
    ) {}
}
