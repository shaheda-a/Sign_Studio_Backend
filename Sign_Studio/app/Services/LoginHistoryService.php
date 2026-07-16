<?php

namespace App\Services;

use App\Models\LoginHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class LoginHistoryService
{
    /**
     * Get login history records with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getLoginHistory(array $filters = [], int $perPage = 15): mixed
    {
        $query = LoginHistory::query()->with('user');

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Log a login event.
     *
     * @param int $userId
     * @param string $status
     * @param string $device
     * @return LoginHistory
     */
    public function logLogin(int $userId, string $status = 'success', string $device = 'Web App'): LoginHistory
    {
        return LoginHistory::create([
            'user_id'      => $userId,
            'ip_address'   => Request::ip(),
            'device'       => $device,
            'status'       => $status,
            'logged_in_at' => Carbon::now(),
        ]);
    }

    /**
     * Log a logout event.
     *
     * @param int $userId
     * @return void
     */
    public function logLogout(int $userId): void
    {
        // Update the last active login history for this user
        $lastLogin = LoginHistory::where('user_id', $userId)
            ->whereNull('logged_out_at')
            ->orderBy('logged_in_at', 'desc')
            ->first();

        if ($lastLogin) {
            $lastLogin->update([
                'logged_out_at' => Carbon::now(),
            ]);
        }
    }
}
