<?php

namespace App\Services\Contract;

use App\Models\Contract;
use App\Models\ContractAddendum;
use Illuminate\Support\Facades\DB;

class ContractAddendumService
{
    public function create(Contract $contract, array $data): ContractAddendum
    {
        return DB::transaction(function () use ($contract, $data): ContractAddendum {
            return ContractAddendum::create([
                ...$data,
                'contract_id' => $contract->id,
                'created_by' => auth()->id(),
            ]);
        });
    }

    public function update(ContractAddendum $contractAddendum, array $data): ContractAddendum
    {
        return DB::transaction( function () use ($contractAddendum, $data): ContractAddendum {
                $contractAddendum->update($data);

                return $contractAddendum->refresh();
            }
        );
    }

    public function delete(ContractAddendum $contractAddendum): void
    {
        DB::transaction(function () use ($contractAddendum): void {
            $contractAddendum->delete();
        });
    }

    public function findForContract(Contract $contract, int $id): ContractAddendum
    {
        return ContractAddendum::query()
            ->where('contract_id', $contract->id)
            ->findOrFail($id);
    }
}