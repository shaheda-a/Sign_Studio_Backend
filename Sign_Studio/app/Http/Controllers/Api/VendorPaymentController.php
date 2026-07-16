<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VendorPaymentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VendorPaymentController extends Controller
{
    use ApiResponseTrait;

    protected $paymentService;

    public function __construct(VendorPaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['vendor_id', 'status']);
        $perPage = $request->input('per_page', 15);
        $payments = $this->paymentService->getAll($filters, $perPage);
        return $this->successResponse($payments, 'Vendor payments retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_mode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        $data = $request->all();
        $data['created_by'] = $request->user()->id ?? null;
        $payment = $this->paymentService->create($data);

        return $this->successResponse($payment, 'Vendor payment recorded successfully.', 201);
    }
}
