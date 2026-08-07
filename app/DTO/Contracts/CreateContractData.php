<?php

namespace App\DTO\Contracts;

final readonly class CreateContractData
{
    public function __construct(
        public int $counterpartyId,
        public string $number,
        public ?string $contractDate,
        public ?string $startDate,
        public ?string $endDate,
        public float $amount,
        public ?string $note,
        public int $createdBy,
    ) {
    }
}