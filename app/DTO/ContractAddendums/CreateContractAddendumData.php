<?php

namespace App\DTO\ContractAddendums;

class CreateContractAddendumData
{
    public function __construct(
        public readonly int $contractId,
        public readonly string $number,
        public readonly string $signedAt,
        public readonly ?string $endDate,
        public readonly float $amountChange,
        public readonly ?string $note,
        public readonly int $createdBy,
    ) {
    }
}