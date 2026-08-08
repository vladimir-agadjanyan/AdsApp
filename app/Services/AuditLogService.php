<?php

namespace App\Services;

use App\DTO\AuditLog\CreateAuditLogData;
use App\Models\AuditLog;
use App\Repositories\AuditLogRepository;

class AuditLogService
{
    public function __construct(private readonly AuditLogRepository $auditLogRepository)
    {
    }

    public function create(CreateAuditLogData $data): AuditLog
    {
        return $this->auditLogRepository->create($data);
    }
}