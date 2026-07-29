<?php

namespace App\Services;

use App\Models\Counterparty;

class CounterpartyService
{
    public function __construct(protected Counterparty $counterparty) {
    }

    public function create(array $data): Counterparty
    {
        return $this->counterparty->create($data);
    }

    public function update(Counterparty $counterparty, array $data): Counterparty
    {
        $counterparty->update($data);

        return $counterparty->refresh();
    }

    public function delete(Counterparty $counterparty): void
    {
        if (! $this->canDelete($counterparty)) {
            throw new \RuntimeException(
                'Невозможно удалить контрагента, так как он используется в договорах.'
            );
        }

        $counterparty->delete();
    }

    public function canDelete(Counterparty $counterparty): bool
    {
        return ! $counterparty->contracts()->exists();
    }

    public function find(int $id): Counterparty
    {
        return $this->counterparty->newQuery()->findOrFail($id);
    }
}