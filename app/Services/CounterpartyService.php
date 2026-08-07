<?php

namespace App\Services;

use App\DTO\Counterparties\CreateCounterpartyData;
use App\DTO\Counterparties\UpdateCounterpartyData;
use App\Models\Counterparty;
use App\Repositories\CounterpartyRepository;
use RuntimeException;

class CounterpartyService
{
    public function __construct(
        private readonly CounterpartyRepository $counterpartyRepository,
    ) {
    }

    public function create(CreateCounterpartyData $data): Counterparty
    {
        return $this->counterpartyRepository->create($data);
    }

    public function update(Counterparty $counterparty, UpdateCounterpartyData $data): Counterparty {
        return $this->counterpartyRepository->update($counterparty, $data);
    }

    public function find(int $id): Counterparty
    {
        return $this->counterpartyRepository->find($id);
    }

    public function canDelete(Counterparty $counterparty): bool
    {
        return ! $counterparty->contracts()->exists();
    }

    public function delete(Counterparty $counterparty): void
    {
        if (! $this->canDelete($counterparty)) {
            throw new RuntimeException(
                'Невозможно удалить контрагента, так как он используется в договорах.'
            );
        }

        $this->counterpartyRepository->delete($counterparty);
    }
}