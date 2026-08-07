<?php

namespace App\Services\Contract;

use App\DTO\Contracts\CreateContractData;
use App\DTO\Contracts\UpdateContractData;
use App\Repositories\ContractRepository;
use App\Models\Contract;
use RuntimeException;

class ContractService
{
    public function __construct(
        private readonly ContractRepository $contractRepository,
    ) {
    }

    public function create(CreateContractData $data): Contract
    {
        return $this->contractRepository->create($data);
    }

    public function update(Contract $contract, UpdateContractData $data): Contract
    {
        return $this->contractRepository->update($contract, $data);
    }

    public function find(int $id): Contract
    {
        return $this->contractRepository->find($id);
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

        $this->contractRepository->delete($contract);
    }
}