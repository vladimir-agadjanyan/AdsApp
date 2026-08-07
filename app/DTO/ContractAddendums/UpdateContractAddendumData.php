<?php

namespace App\DTO\ContractAddendums;

class UpdateContractAddendumData
{
    public function __construct(
        public readonly string $number,
        public readonly string $signedAt,
        public readonly ?string $endDate,
        public readonly float $amountChange,
        public readonly ?string $note,
    ) {}
}
