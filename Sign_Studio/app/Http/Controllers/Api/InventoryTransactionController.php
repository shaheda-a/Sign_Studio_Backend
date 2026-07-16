<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\InventoryTransactionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InventoryTransactionController extends Controller
{
    use ApiResponseTrait;

    protected $transactionService;

    public function __construct(InventoryTransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['item_id', 'type']);
        $perPage = $request->input('per_page', 15);
        $transactions = $this->transactionService->getAll($filters, $perPage);
        return $this->successResponse($transactions, 'Inventory transactions retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'type' => 'required|string|in:in,out',
            'qty' => 'required|numeric|min:0.001',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        $data = $request->all();
        $data['created_by'] = $request->user()->id ?? null;
        $data['date'] = now();
        // Assuming balance_qty will be handled or calculated in a real scenario
        $data['balance_qty'] = 0; 

        $transaction = $this->transactionService->create($data);

        return $this->successResponse($transaction, 'Inventory transaction recorded successfully.', 201);
    }
}
