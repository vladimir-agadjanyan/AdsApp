<?php

namespace App\Repositories;

use App\DTO\Contracts\CreateContractData;
use App\DTO\Contracts\UpdateContractData;
use App\Models\Contract;

class ContractRepository
{
    public function create(CreateContractData $data): Contract
    {
        return Contract::create([
            'counterparty_id' => $data->counterpartyId,
            'number' => $data->number,
            'contract_date' => $data->contractDate,
            'start_date' => $data->startDate,
            'end_date' => $data->endDate,
            'amount' => $data->amount,
            'note' => $data->note,
            'created_by' => $data->createdBy,
        ]);
    }

    public function update(Contract $contract, UpdateContractData $data): Contract
    {
        $contract->update([
            'counterparty_id' => $data->counterpartyId,
            'number' => $data->number,
            'contract_date' => $data->contractDate,
            'start_date' => $data->startDate,
            'end_date' => $data->endDate,
            'amount' => $data->amount,
            'note' => $data->note,
        ]);

        return $contract;
    }

    public function find(int $id): Contract
    {
        return Contract::findOrFail($id);
    }

    public function delete(Contract $contract): void
    {
        $contract->delete();
    }
}