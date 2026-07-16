<?php

namespace App\Services;

use App\Models\ApiKey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ApiKeyService
{
    /**
     * Get API keys with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getKeys(array $filters = [], int $perPage = 15): mixed
    {
        $query = ApiKey::query();

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'desc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create an API key with a generated key string.
     *
     * @param array $data
     * @return ApiKey
     */
    public function createKey(array $data): ApiKey
    {
        $data['key'] = 'sk_' . Str::random(48);
        $data['created_by'] = Auth::id();
        return ApiKey::create($data);
    }

    /**
     * Get API key by ID.
     *
     * @param int $id
     * @return ApiKey
     */
    public function getKeyById(int $id): ApiKey
    {
        return ApiKey::findOrFail($id);
    }

    /**
     * Update an API key.
     *
     * @param int $id
     * @param array $data
     * @return ApiKey
     */
    public function updateKey(int $id, array $data): ApiKey
    {
        $key = ApiKey::findOrFail($id);
        $key->update($data);
        return $key;
    }

    /**
     * Delete an API key.
     *
     * @param int $id
     * @return bool
     */
    public function deleteKey(int $id): bool
    {
        $key = ApiKey::findOrFail($id);
        return $key->delete();
    }

    /**
     * Restore a deleted API key.
     *
     * @param int $id
     * @return ApiKey
     */
    public function restoreKey(int $id): ApiKey
    {
        $key = ApiKey::withTrashed()->findOrFail($id);
        $key->restore();
        return $key;
    }
}
