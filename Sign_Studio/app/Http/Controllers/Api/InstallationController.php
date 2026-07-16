<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Installation;
use App\Services\InstallationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Validator;
use Exception;

class InstallationController extends Controller
{
    use ApiResponseTrait;

    protected $installationService;

    public function __construct(InstallationService $installationService)
    {
        $this->installationService = $installationService;
    }

    public function index(Request $request)
    {
        try {
            $installations = Installation::with(['order', 'assignedTo', 'createdBy'])->get();
            return $this->successResponse($installations, 'Installations retrieved successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to retrieve installations: ' . $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required|exists:orders,id',
            'installation_number' => 'nullable|string|max:50|unique:installations,installation_number',
            'scheduled_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation Error', 422, $validator->errors()->toArray());
        }

        try {
            $installation = $this->installationService->createInstallation($request->all(), $request->user()->id);
            return $this->successResponse($installation, 'Installation created successfully.', 201);
        } catch (Exception $e) {
            return $this->errorResponse('Failed to create installation: ' . $e->getMessage(), 500);
        }
    }

    public function show($id)
    {
        try {
            $installation = Installation::with([
                'order', 'dispatch', 'assignedTo', 'createdBy',
                'materialConfirmations', 'gpsLogs', 'photos',
                'corrections', 'signoff', 'score'
            ])->findOrFail($id);
            return $this->successResponse($installation, 'Installation retrieved successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Installation not found: ' . $e->getMessage(), 404);
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
            $installation = Installation::findOrFail($id);
            $installation = $this->installationService->updateInstallation($installation, $request->all());
            return $this->successResponse($installation, 'Installation updated successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to update installation: ' . $e->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $installation = Installation::findOrFail($id);
            $this->installationService->deleteInstallation($installation);
            return $this->successResponse(null, 'Installation deleted successfully.');
        } catch (Exception $e) {
            return $this->errorResponse('Failed to delete installation: ' . $e->getMessage(), 500);
        }
    }
}
