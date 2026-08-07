<?php

namespace App\Repositories;

use App\DTO\Contracts\CreateContractData;
use App\DTO\Contracts\UpdateContractData;
use App\Models\Contract;
use Illuminate\Database\Eloquent\Collection;

class ContractRepository
{
    public function __construct(private readonly Contract $contract)
    {
    }

    public function create(CreateContractData $data): Contract
    {
        return $this->contract
            ->newQuery()
            ->create([
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

        return $contract->refresh();
    }

    public function getExpiringContracts(int $days): Collection
    {
        return $this->contract
            ->newQuery()
            ->with(['counterparty', 'createdBy'])
            ->whereDate('end_date', now()->addDays($days)->toDateString())
            ->get();
    }

    public function find(int $id): Contract
    {
        return $this->contract
            ->newQuery()
            ->findOrFail($id);
    }

    public function delete(Contract $contract): void
    {
        $contract->delete();
    }
}