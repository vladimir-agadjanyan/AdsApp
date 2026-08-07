<?php

namespace App\Repositories;

use App\Models\ContractFile;

class ContractFileRepository
{
    public function create(array $data): ContractFile
    {
        return ContractFile::create($data);
    }

    public function delete(ContractFile $file): void
    {
        $file->delete();
    }
}