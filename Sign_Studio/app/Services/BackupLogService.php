<?php

namespace App\Services;

use App\Models\BackupLog;
use Illuminate\Support\Facades\Auth;

class BackupLogService
{
    /**
     * Get backup logs with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getLogs(array $filters = [], int $perPage = 15): mixed
    {
        $query = BackupLog::query();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a backup log.
     *
     * @param array $data
     * @return BackupLog
     */
    public function createLog(array $data): BackupLog
    {
        $data['created_by'] = Auth::id();
        return BackupLog::create($data);
    }

    /**
     * Get backup log by ID.
     *
     * @param int $id
     * @return BackupLog
     */
    public function getLogById(int $id): BackupLog
    {
        return BackupLog::findOrFail($id);
    }
}
