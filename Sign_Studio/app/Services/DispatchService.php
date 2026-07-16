<?php

namespace App\Services;

use App\Models\Dispatch;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Exception;

class DispatchService
{
    /**
     * Create a new dispatch record
     */
    public function createDispatch(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['created_by'] = $userId;
            
            // Generate unique dispatch number if not provided
            if (empty($data['dispatch_number'])) {
                $data['dispatch_number'] = 'DSP-' . date('Ymd') . '-' . rand(1000, 9999);
            }
            
            if (!isset($data['status'])) {
                $data['status'] = 'packing';
            }
            
            $dispatch = Dispatch::create($data);

            return $dispatch;
        });
    }

    /**
     * Update dispatch details
     */
    public function updateDispatch(Dispatch $dispatch, array $data)
    {
        return DB::transaction(function () use ($dispatch, $data) {
            $dispatch->update($data);
            return $dispatch;
        });
    }

    /**
     * Delete a dispatch
     */
    public function deleteDispatch(Dispatch $dispatch)
    {
        return DB::transaction(function () use ($dispatch) {
            $dispatch->delete();
            return true;
        });
    }
}
