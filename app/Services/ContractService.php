<?php

namespace App\Services;

use App\Models\Contract;
use RuntimeException;

class ContractService
{
    /**
     * Создает договор.
     */
    public function create(array $data): Contract
    {
        return Contract::create($data);
    }

    /**
     * Обновляет договор.
     */
    public function update(Contract $contract, array $data): Contract
    {
        $contract->update($data);

        return $contract;
    }

    /**
     * Возвращает договор по ID.
     */
    public function find(int $id): Contract
    {
        return Contract::findOrFail($id);
    }

    /**
     * Проверяет, можно ли удалить договор.
     */
    public function canDelete(Contract $contract): bool
    {
        // Здесь можно добавить бизнес-проверки.
        // Например:
        // - есть дополнительные соглашения;
        // - есть фотоотчеты;
        // - договор подтвержден и запрещен к удалению.
        return true;
    }

    /**
     * Удаляет договор.
     */
    public function delete(Contract $contract): void
    {
        if (! $this->canDelete($contract)) {
            throw new RuntimeException(
                'Нельзя удалить договор.'
            );
        }

        $contract->delete();
    }
}