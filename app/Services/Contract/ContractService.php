<?php

namespace App\Services\Contract;

use App\Models\Contract;
use RuntimeException;

class ContractService
{
    public function create(array $data): Contract
    {
        return Contract::create($data);
    }

    public function update(Contract $contract, array $data): Contract
    {
        $contract->update($data);

        return $contract;
    }

    public function find(int $id): Contract
    {
        return Contract::findOrFail($id);
    }

    public function canDelete(Contract $contract): bool
    {
        return ! $contract->advertisingObjects()->exists();
    }

    public function delete(Contract $contract): void
    {
        if (! $this->canDelete($contract)) {
            throw new RuntimeException(
                'Нельзя удалить договор, так как к нему привязаны рекламные объекты.'
            );
        }

        $contract->delete();
    }
}