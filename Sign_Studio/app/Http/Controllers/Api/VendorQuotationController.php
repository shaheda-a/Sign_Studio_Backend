<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VendorQuotationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorQuotationController extends Controller
{
    use ApiResponseTrait;

    protected $quotationService;

    public function __construct(VendorQuotationService $quotationService)
    {
        $this->quotationService = $quotationService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['vendor_id', 'item_id']);
        $perPage = $request->input('per_page', 15);
        $quotations = $this->quotationService->getAll($filters, $perPage);
        return $this->successResponse($quotations, 'Vendor quotations retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'item_id' => 'required|exists:items,id',
            'quoted_price' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        $data = $request->all();
        $data['created_by'] = $request->user()->id ?? null;
        $quotation = $this->quotationService->create($data);

        return $this->successResponse($quotation, 'Vendor quotation created successfully.', 201);
    }

    public function show($id)
    {
        $quotation = $this->quotationService->findById($id);
        return $this->successResponse($quotation, 'Vendor quotation retrieved successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quoted_price' => 'sometimes|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        $quotation = $this->quotationService->update($id, $request->all());
        return $this->successResponse($quotation, 'Vendor quotation updated successfully.');
    }

    public function destroy($id)
    {
        $this->quotationService->delete($id);
        return $this->successResponse(null, 'Vendor quotation deleted successfully.');
    }
}
