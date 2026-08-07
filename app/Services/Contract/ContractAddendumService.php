<?php

namespace App\Services\Contract;

use App\DTO\ContractAddendums\CreateContractAddendumData;
use App\DTO\ContractAddendums\UpdateContractAddendumData;
use App\Models\Contract;
use App\Models\ContractAddendum;
use App\Repositories\ContractAddendumRepository;
use Illuminate\Support\Facades\DB;

class ContractAddendumService
{
    public function __construct(
        private readonly ContractAddendumRepository $contractAddendumRepository,
    ) {
    }

    public function create(CreateContractAddendumData $data): ContractAddendum
    {
        return DB::transaction(function () use ($data): ContractAddendum {

            return $this->contractAddendumRepository->create($data);

        });
    }

    public function update(ContractAddendum $contractAddendum, UpdateContractAddendumData $data): ContractAddendum
    {
        return DB::transaction(function () use (
            $contractAddendum,
            $data,
        ): ContractAddendum {

            $contractAddendum = $this->contractAddendumRepository->update(
                $contractAddendum,
                $data,
            );

            return $contractAddendum->refresh();
        });
    }

    public function delete(ContractAddendum $contractAddendum): void
    {
        DB::transaction(function () use ($contractAddendum): void {

            $this->contractAddendumRepository->delete(
                $contractAddendum
            );

        });
    }

    public function findForContract(Contract $contract, int $id): ContractAddendum
    {
        return $this->contractAddendumRepository->findForContract(
            $contract,
            $id,
        );
    }
}