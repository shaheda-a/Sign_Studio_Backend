<?php

namespace App\Services;

use App\Models\Item;

class ItemService
{
    public function getAll(array $filters = [], int $perPage = 15)
    {
        $query = Item::query();
        if (!empty($filters['category'])) {
            $query->where('category', $filters['category']);
        }
        if (!empty($filters['search'])) {
            $query->where('name', 'like', '%' . $filters['search'] . '%')
                  ->orWhere('sku_code', 'like', '%' . $filters['search'] . '%');
        }
        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return Item::create($data);
    }

    public function findById($id)
    {
        return Item::findOrFail($id);
    }

    public function update($id, array $data)
    {
        $item = $this->findById($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = $this->findById($id);
        $item->delete();
        return true;
    }

    public function restore($id)
    {
        $item = Item::withTrashed()->findOrFail($id);
        $item->restore();
        return $item;
    }
}
