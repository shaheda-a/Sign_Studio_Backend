<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VendorService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    use ApiResponseTrait;

    protected $vendorService;

    public function __construct(VendorService $vendorService)
    {
        $this->vendorService = $vendorService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'search']);
        $perPage = $request->input('per_page', 15);
        $vendors = $this->vendorService->getAll($filters, $perPage);
        return $this->successResponse($vendors, 'Vendors retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:300',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'status' => 'string|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        $data = $request->all();
        $data['created_by'] = $request->user()->id ?? null;
        $vendor = $this->vendorService->create($data);

        return $this->successResponse($vendor, 'Vendor created successfully.', 201);
    }

    public function show($id)
    {
        $vendor = $this->vendorService->findById($id);
        return $this->successResponse($vendor, 'Vendor retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:300',
            'email' => 'nullable|email|max:150',
            'phone' => 'nullable|string|max:20',
            'status' => 'string|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        $vendor = $this->vendorService->update($id, $request->all());
        return $this->successResponse($vendor, 'Vendor updated successfully.');
    }

    public function destroy($id)
    {
        $this->vendorService->delete($id);
        return $this->successResponse(null, 'Vendor deleted successfully.');
    }
}
