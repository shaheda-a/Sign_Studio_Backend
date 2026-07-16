<?php

namespace App\Services;

use App\Models\Installation;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Exception;

class InstallationService
{
    /**
     * Create a new installation record
     */
    public function createInstallation(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            $data['created_by'] = $userId;
            
            // Generate unique installation number if not provided
            if (empty($data['installation_number'])) {
                $data['installation_number'] = 'INS-' . date('Ymd') . '-' . rand(1000, 9999);
            }
            
            if (!isset($data['status'])) {
                $data['status'] = 'pending';
            }
            
            $installation = Installation::create($data);

            return $installation;
        });
    }

    /**
     * Update installation details
     */
    public function updateInstallation(Installation $installation, array $data)
    {
        return DB::transaction(function () use ($installation, $data) {
            $installation->update($data);
            return $installation;
        });
    }

    /**
     * Delete an installation
     */
    public function deleteInstallation(Installation $installation)
    {
        return DB::transaction(function () use ($installation) {
            $installation->delete();
            return true;
        });
    }
}
