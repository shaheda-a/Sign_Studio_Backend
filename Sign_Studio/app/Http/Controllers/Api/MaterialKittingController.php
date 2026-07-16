<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MaterialKittingService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class MaterialKittingController extends Controller
{
    use ApiResponseTrait;

    protected $kittingService;

    public function __construct(MaterialKittingService $kittingService)
    {
        $this->kittingService = $kittingService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['status', 'kit_number']);
        $perPage = $request->input('per_page', 15);
        $kittings = $this->kittingService->getAll($filters, $perPage);
        return $this->successResponse($kittings, 'Material kittings retrieved successfully.');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kit_number' => 'required|string|max:50|unique:material_kitting',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.required_qty' => 'required|numeric|min:0.001',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        try {
            DB::beginTransaction();

            $data = $request->except('items');
            $data['created_by'] = $request->user()->id ?? null;
            $kitting = $this->kittingService->create($data);

            foreach ($request->items as $itemData) {
                $kitting->items()->create([
                    'item_id' => $itemData['item_id'],
                    'required_qty' => $itemData['required_qty'],
                    'issued_qty' => 0,
                    'created_by' => $data['created_by']
                ]);
            }

            DB::commit();
            return $this->successResponse($kitting->load('items'), 'Material kitting created successfully.', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Failed to create material kitting: ' . $e->getMessage(), 500);
        }
    }

    public function issueKit(Request $request, $id)
    {
        $kitting = $this->kittingService->issueKit($id, $request->user()->id ?? null);
        return $this->successResponse($kitting, 'Material kit issued successfully.');
    }
}
