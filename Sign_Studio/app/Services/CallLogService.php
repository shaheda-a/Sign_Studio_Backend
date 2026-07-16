<?php

namespace App\Services;

use App\Models\CallLog;
use Illuminate\Support\Facades\Auth;

class CallLogService
{
    /**
     * Get call logs.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getCallLogs(array $filters = [], int $perPage = 15): mixed
    {
        $query = CallLog::query()->with(['lead', 'customer', 'user']);

        if (!empty($filters['lead_id'])) {
            $query->where('lead_id', $filters['lead_id']);
        }

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'desc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create call log entry.
     *
     * @param array $data
     * @return CallLog
     */
    public function createCallLog(array $data): CallLog
    {
        $data['created_by'] = Auth::id();
        if (empty($data['user_id'])) {
            $data['user_id'] = Auth::id();
        }
        if (empty($data['called_at'])) {
            $data['called_at'] = now();
        }
        return CallLog::create($data);
    }

    /**
     * Get call log by ID.
     *
     * @param int $id
     * @return CallLog
     */
    public function getCallLogById(int $id): CallLog
    {
        return CallLog::with(['lead', 'customer', 'user'])->findOrFail($id);
    }

    /**
     * Delete call log entry.
     *
     * @param int $id
     * @return bool
     */
    public function deleteCallLog(int $id): bool
    {
        $log = CallLog::findOrFail($id);
        return $log->delete();
    }
}
