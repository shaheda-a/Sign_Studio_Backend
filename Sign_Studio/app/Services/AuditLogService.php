<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Retrieve audit logs with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getAuditLogs(array $filters = [], int $perPage = 15): mixed
    {
        $query = AuditLog::query()->with('user');

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['event'])) {
            $query->where('event', $filters['event']);
        }

        if (!empty($filters['auditable_type'])) {
            $query->where('auditable_type', $filters['auditable_type']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create an audit log entry.
     *
     * @param int|null $userId
     * @param string $event
     * @param string $auditableType
     * @param int $auditableId
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return AuditLog
     */
    public function logEvent(
        ?int $userId,
        string $event,
        string $auditableType,
        int $auditableId,
        ?array $oldValues = null,
        ?array $newValues = null
    ): AuditLog {
        return AuditLog::create([
            'user_id'        => $userId,
            'event'          => $event,
            'auditable_type' => $auditableType,
            'auditable_id'   => $auditableId,
            'old_values'     => $oldValues,
            'new_values'     => $newValues,
            'ip_address'     => Request::ip(),
            'user_agent'     => Request::userAgent(),
        ]);
    }
}
