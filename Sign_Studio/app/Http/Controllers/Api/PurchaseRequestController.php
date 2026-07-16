<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PurchaseRequestService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PurchaseRequestController extends Controller
{
    use ApiResponseTrait;

    protected $prService;

    public function __construct(PurchaseRequestService $prService)
    {
        $this->prService = $prService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'pr_number']);
        $perPage = $request->input('per_page', 15);
        $prs = $this->prService->getAll($filters, $perPage);
        return $this->successResponse($prs, 'Purchase requests retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pr_number' => 'required|string|max:50|unique:purchase_requests',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.qty' => 'required|numeric|min:0.001',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            $data = $request->except('items');
            $data['created_by'] = $request->user()->id ?? null;
            $data['requested_by'] = $request->user()->id ?? null;
            $pr = $this->prService->create($data);

            foreach ($request->items as $itemData) {
                $pr->items()->create([
                    'item_id' => $itemData['item_id'],
                    'qty' => $itemData['qty'],
                    'notes' => $itemData['notes'] ?? null,
                    'created_by' => $data['created_by']
                ]);
            }

            DB::commit();
            return $this->successResponse($pr->load('items'), 'Purchase request created successfully.', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create purchase request: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        $pr = $this->prService->findById($id);
        return $this->successResponse($pr, 'Purchase request retrieved successfully.');
    }
}
