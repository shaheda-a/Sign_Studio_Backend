<?php

namespace App\Services;

use App\Models\CustomerContact;
use Illuminate\Support\Facades\Auth;

class CustomerContactService
{
    /**
     * Get contacts with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getContacts(array $filters = [], int $perPage = 15): mixed
    {
        $query = CustomerContact::query()->with('customer');

        if (!empty($filters['customer_id'])) {
            $query->where('customer_id', $filters['customer_id']);
        }

        if ($perPage === -1) {
            return $query->orderBy('name', 'asc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a customer contact.
     *
     * @param array $data
     * @return CustomerContact
     */
    public function createContact(array $data): CustomerContact
    {
        $data['created_by'] = Auth::id();

        if (isset($data['is_primary']) && (int) $data['is_primary'] === 1) {
            CustomerContact::where('customer_id', $data['customer_id'])
                ->update(['is_primary' => 0]);
        }

        return CustomerContact::create($data);
    }

    /**
     * Get contact by ID.
     *
     * @param int $id
     * @return CustomerContact
     */
    public function getContactById(int $id): CustomerContact
    {
        return CustomerContact::with('customer')->findOrFail($id);
    }

    /**
     * Update a contact.
     *
     * @param int $id
     * @param array $data
     * @return CustomerContact
     */
    public function updateContact(int $id, array $data): CustomerContact
    {
        $contact = CustomerContact::findOrFail($id);

        if (isset($data['is_primary']) && (int) $data['is_primary'] === 1) {
            CustomerContact::where('customer_id', $contact->customer_id)
                ->where('id', '!=', $id)
                ->update(['is_primary' => 0]);
        }

        $contact->update($data);
        return $contact;
    }

    /**
     * Delete a contact.
     *
     * @param int $id
     * @return bool
     */
    public function deleteContact(int $id): bool
    {
        $contact = CustomerContact::findOrFail($id);
        return $contact->delete();
    }

    /**
     * Restore a deleted contact.
     *
     * @param int $id
     * @return CustomerContact
     */
    public function restoreContact(int $id): CustomerContact
    {
        $contact = CustomerContact::withTrashed()->findOrFail($id);
        $contact->restore();
        return $contact;
    }
}
