<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PurchaseOrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    use ApiResponseTrait;

    protected $poService;

    public function __construct(PurchaseOrderService $poService)
    {
        $this->poService = $poService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'po_number']);
        $perPage = $request->input('per_page', 15);
        $pos = $this->poService->getAll($filters, $perPage);
        return $this->successResponse($pos, 'Purchase orders retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vendor_id' => 'required|exists:vendors,id',
            'po_number' => 'required|string|max:50|unique:purchase_orders',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|min:0.001',
            'items.*.rate' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            $data = $request->except('items');
            $data['created_by'] = $request->user()->id ?? null;
            
            // Calculate totals
            $totalAmount = 0;
            $taxAmount = 0;
            
            foreach ($request->items as $item) {
                $totalAmount += ($item['qty'] * $item['rate']);
                // assuming tax is passed or calculated elsewhere. simplified for now.
            }
            $data['total_amount'] = $totalAmount;
            $data['tax_amount'] = $taxAmount;
            $data['grand_total'] = $totalAmount + $taxAmount;

            $po = $this->poService->create($data);

            foreach ($request->items as $itemData) {
                $po->items()->create([
                    'item_id' => $itemData['item_id'],
                    'qty' => $itemData['qty'],
                    'rate' => $itemData['rate'],
                    'tax_rate' => $itemData['tax_rate'] ?? 0,
                    'total' => ($itemData['qty'] * $itemData['rate']),
                    'created_by' => $data['created_by']
                ]);
            }

            DB::commit();
            return $this->successResponse($po->load('items'), 'Purchase order created successfully.', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create purchase order: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $po = $this->poService->findById($id);
        return $this->successResponse($po, 'Purchase order retrieved successfully.');
    }
}
