<?php

namespace App\DTO\AuditLog;

final readonly class CreateAuditLogData
{
    public function __construct(
        public int $userId,
        public string $action,
        public string $entityType,
        public int $entityId,
        public string $description,
        public ?array $oldValues,
        public ?array $newValues,
    ) {
    }
}