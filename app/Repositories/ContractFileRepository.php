<?php

namespace App\Repositories;

use App\Models\ContractFile;

class ContractFileRepository
{
    public function __construct(private readonly ContractFile $contractFile) {}

    public function create(array $data): ContractFile
    {
        return $this->contractFile->create($data);
    }

    public function find(int $id): ContractFile
    {
        return $this->contractFile
            ->newQuery()
            ->findOrFail($id);
    }

    public function delete(ContractFile $contractFile): void
    {
        $contractFile->delete();
    }
}
