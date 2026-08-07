<?php

namespace App\DTO\Counterparties;

readonly class UpdateCounterpartyData
{
    public function __construct(
        public string $name,
        public string $inn,
        public string $phone,
        public ?string $email,
        public ?string $address,
        public string $contactPerson,
        public ?string $note,
    ) {
    }
}