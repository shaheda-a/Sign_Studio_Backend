<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Dispatch;
use App\Services\DispatchService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;
use Exception;

class DispatchController extends Controller
{
    use ApiResponseTrait;

    protected $dispatchService;

    public function __construct(DispatchService $dispatchService)
    {
        $this->dispatchService = $dispatchService;
    }

    public function index(Request $request)
    {
        try {
            $dispatches = Dispatch::with(['order', 'createdBy', 'dispatchItems'])->get();
            return $this->successResponse($dispatches, 'Dispatches retrieved successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve dispatches: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'dispatch_number' => 'nullable|string|max:50|unique:dispatches,dispatch_number',
            'expected_delivery' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        try {
            $dispatch = $this->dispatchService->createDispatch($request->all(), $request->user()->id);
            return $this->successResponse($dispatch, 'Dispatch created successfully.', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create dispatch: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $dispatch = Dispatch::with([
                'order', 'createdBy', 'dispatchItems', 'packingChecklists', 
                'dispatchApprovals', 'dispatchProofs', 'vehicleTrackingLogs', 
                'deliveryConfirmation', 'installation'
            ])->findOrFail($id);
            return $this->successResponse($dispatch, 'Dispatch retrieved successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Dispatch not found: ' . $e->getMessage(), 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|string|max:30',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        try {
            $dispatch = Dispatch::findOrFail($id);
            $dispatch = $this->dispatchService->updateDispatch($dispatch, $request->all());
            return $this->successResponse($dispatch, 'Dispatch updated successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update dispatch: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $dispatch = Dispatch::findOrFail($id);
            $this->dispatchService->deleteDispatch($dispatch);
            return $this->successResponse(null, 'Dispatch deleted successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete dispatch: ' . $e->getMessage(), 500);
        }
    }
}
