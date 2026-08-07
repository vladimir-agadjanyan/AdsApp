<?php

namespace App\Repositories;

use App\DTO\ContractAddendums\CreateContractAddendumData;
use App\DTO\ContractAddendums\UpdateContractAddendumData;
use App\Models\Contract;
use App\Models\ContractAddendum;

class ContractAddendumRepository
{
    public function __construct(private readonly ContractAddendum $contractAddendum) {}

    public function create(CreateContractAddendumData $data): ContractAddendum
    {
        return $this->contractAddendum->create([
            'contract_id' => $data->contractId,
            'number' => $data->number,
            'signed_at' => $data->signedAt,
            'end_date' => $data->endDate,
            'amount_change' => $data->amountChange,
            'note' => $data->note,
            'created_by' => $data->createdBy,
        ]);
    }

    public function update(ContractAddendum $contractAddendum, UpdateContractAddendumData $data): ContractAddendum
    {
        $contractAddendum->update([
            'number' => $data->number,
            'signed_at' => $data->signedAt,
            'end_date' => $data->endDate,
            'amount_change' => $data->amountChange,
            'note' => $data->note,
        ]);

        return $contractAddendum;
    }

    public function findForContract(Contract $contract, int $id): ContractAddendum
    {
        return $this->contractAddendum
            ->newQuery()
            ->where('contract_id', $contract->id)
            ->findOrFail($id);
    }

    public function delete(ContractAddendum $contractAddendum): void
    {
        $contractAddendum->delete();
    }
}
