<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MaterialConsumptionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaterialConsumptionController extends Controller
{
    use ApiResponseTrait;

    protected $consumptionService;

    public function __construct(MaterialConsumptionService $consumptionService)
    {
        $this->consumptionService = $consumptionService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['order_id', 'production_plan_id']);
        $perPage = $request->input('per_page', 15);
        $consumptions = $this->consumptionService->getAll($filters, $perPage);
        return $this->successResponse($consumptions, 'Material consumptions retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_id' => 'required|exists:items,id',
            'consumed_qty' => 'required|numeric|min:0.001',
            'wastage_qty' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        $data = $request->all();
        $data['created_by'] = $request->user()->id ?? null;
        $data['consumed_by'] = $request->user()->id ?? null;
        $data['consumed_at'] = now();

        $consumption = $this->consumptionService->create($data);

        return $this->successResponse($consumption, 'Material consumption recorded successfully.', 201);
    }
}
