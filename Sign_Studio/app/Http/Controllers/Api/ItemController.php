<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ItemService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    use ApiResponseTrait;

    protected $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['category', 'search']);
        $perPage = $request->input('per_page', 15);
        $items = $this->itemService->getAll($filters, $perPage);
        return $this->successResponse($items, 'Items retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sku_code' => 'required|string|max:50|unique:items',
            'name' => 'required|string|max:300',
            'category' => 'nullable|string|max:100',
            'reorder_level' => 'integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        $data = $request->all();
        $data['created_by'] = $request->user()->id ?? null;
        $item = $this->itemService->create($data);

        return $this->successResponse($item, 'Item created successfully.', 201);
    }

    public function show($id)
    {
        $item = $this->itemService->findById($id);
        return $this->successResponse($item, 'Item retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sku_code' => 'sometimes|string|max:50|unique:items,sku_code,'.$id,
            'name' => 'sometimes|string|max:300',
            'category' => 'nullable|string|max:100',
            'reorder_level' => 'integer|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        $item = $this->itemService->update($id, $request->all());
        return $this->successResponse($item, 'Item updated successfully.');
    }

    public function destroy($id)
    {
        $this->itemService->delete($id);
        return $this->successResponse(null, 'Item deleted successfully.');
    }
}
