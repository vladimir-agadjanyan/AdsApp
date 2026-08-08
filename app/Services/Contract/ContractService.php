<?php

namespace App\Services\Contract;

use App\DTO\AuditLog\CreateAuditLogData;
use App\DTO\Contracts\CreateContractData;
use App\DTO\Contracts\UpdateContractData;
use App\Models\Contract;
use App\Repositories\ContractRepository;
use App\Services\AuditLogService;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

class ContractService
{
    public function __construct(
        private readonly ContractRepository $contractRepository,
        private readonly AuditLogService $auditLogService,
    ) {
    }

    public function create(CreateContractData $data): Contract
    {
        $contract = $this->contractRepository->create($data);

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'created',
                entityType: Contract::class,
                entityId: $contract->id,
                description: "Создан договор №{$contract->number}",
                oldValues: null,
                newValues: [
                    'number' => $contract->number,
                    'counterparty_id' => $contract->counterparty_id,
                    'contract_date' => $contract->contract_date?->format('Y-m-d'),
                    'start_date' => $contract->start_date?->format('Y-m-d'),
                    'end_date' => $contract->end_date?->format('Y-m-d'),
                    'amount' => $contract->amount,
                    'note' => $contract->note,
                ],
            ),
        );

        return $contract;
    }

    public function update(Contract $contract, UpdateContractData $data): Contract
    {
        $oldValues = [
            'number' => $contract->number,
            'counterparty_id' => $contract->counterparty_id,
            'contract_date' => $contract->contract_date?->format('Y-m-d'),
            'start_date' => $contract->start_date?->format('Y-m-d'),
            'end_date' => $contract->end_date?->format('Y-m-d'),
            'amount' => $contract->amount,
            'note' => $contract->note,
        ];

        $contract = $this->contractRepository->update($contract, $data);

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'updated',
                entityType: Contract::class,
                entityId: $contract->id,
                description: "Изменен договор №{$contract->number}",
                oldValues: $oldValues,
                newValues: [
                    'number' => $contract->number,
                    'counterparty_id' => $contract->counterparty_id,
                    'contract_date' => $contract->contract_date?->format('Y-m-d'),
                    'start_date' => $contract->start_date?->format('Y-m-d'),
                    'end_date' => $contract->end_date?->format('Y-m-d'),
                    'amount' => $contract->amount,
                    'note' => $contract->note,
                ],
            ),
        );

        return $contract;
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
                'Нельзя удалить договор, так как к нему привязаны рекламные объекты.',
            );
        }

        $contractNumber = $contract->number;

        $oldValues = [
            'number' => $contract->number,
            'counterparty_id' => $contract->counterparty_id,
            'contract_date' => $contract->contract_date?->format('Y-m-d'),
            'start_date' => $contract->start_date?->format('Y-m-d'),
            'end_date' => $contract->end_date?->format('Y-m-d'),
            'amount' => $contract->amount,
            'note' => $contract->note,
        ];

        $this->contractRepository->delete($contract);

        $this->auditLogService->create(
            new CreateAuditLogData(
                userId: Auth::id(),
                action: 'deleted',
                entityType: Contract::class,
                entityId: $contract->id,
                description: "Удален договор №{$contractNumber}",
                oldValues: $oldValues,
                newValues: null,
            ),
        );
    }
}