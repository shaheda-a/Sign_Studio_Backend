<?php

namespace App\Services;

use App\Models\BookingToken;
use Illuminate\Support\Facades\Auth;

class BookingTokenService
{
    /**
     * Get booking tokens with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getTokens(array $filters = [], int $perPage = 15): mixed
    {
        $query = BookingToken::query()->with(['lead', 'customer', 'receivedByUser']);

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
     * Create booking token record.
     *
     * @param array $data
     * @return BookingToken
     */
    public function createToken(array $data): BookingToken
    {
        $data['created_by'] = Auth::id();
        if (empty($data['received_by'])) {
            $data['received_by'] = Auth::id();
        }
        if (empty($data['received_at'])) {
            $data['received_at'] = now();
        }
        return BookingToken::create($data);
    }

    /**
     * Get booking token by ID.
     *
     * @param int $id
     * @return BookingToken
     */
    public function getTokenById(int $id): BookingToken
    {
        return BookingToken::with(['lead', 'customer', 'receivedByUser'])->findOrFail($id);
    }

    /**
     * Update booking token.
     *
     * @param int $id
     * @param array $data
     * @return BookingToken
     */
    public function updateToken(int $id, array $data): BookingToken
    {
        $token = BookingToken::findOrFail($id);
        $token->update($data);
        return $token;
    }

    /**
     * Delete booking token.
     *
     * @param int $id
     * @return bool
     */
    public function deleteToken(int $id): bool
    {
        $token = BookingToken::findOrFail($id);
        return $token->delete();
    }

    /**
     * Restore booking token.
     *
     * @param int $id
     * @return BookingToken
     */
    public function restoreToken(int $id): BookingToken
    {
        $token = BookingToken::withTrashed()->findOrFail($id);
        $token->restore();
        return $token;
    }
}
