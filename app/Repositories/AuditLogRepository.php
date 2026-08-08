<?php

namespace App\Repositories;

use App\DTO\AuditLog\CreateAuditLogData;
use App\Models\AuditLog;

class AuditLogRepository
{
    public function __construct(
        private readonly AuditLog $auditLog,
    ) {
    }

    public function create(CreateAuditLogData $data): AuditLog
    {
        return $this->auditLog->create([
            'user_id' => $data->userId,
            'action' => $data->action,
            'entity_type' => $data->entityType,
            'entity_id' => $data->entityId,
            'description' => $data->description,
            'old_values' => $data->oldValues,
            'new_values' => $data->newValues,
        ]);
    }
}