<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesignApprovalRequest;
use App\Http\Resources\DesignApprovalResource;
use App\Services\DesignApprovalService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DesignApprovalController extends Controller
{
    use ApiResponseTrait;

    protected DesignApprovalService $designApprovalService;

    public function __construct(DesignApprovalService $designApprovalService)
    {
        $this->designApprovalService = $designApprovalService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['design_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $approvals = $this->designApprovalService->getApprovals($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Design approvals retrieved successfully', DesignApprovalResource::collection($approvals));
        }

        return $this->successResponse('Design approvals retrieved successfully', [
            'items' => DesignApprovalResource::collection($approvals->items()),
            'meta'  => [
                'current_page' => $approvals->currentPage(),
                'last_page'    => $approvals->lastPage(),
                'per_page'     => $approvals->perPage(),
                'total'        => $approvals->total(),
            ],
        ]);
    }

    public function store(DesignApprovalRequest $request): JsonResponse
    {
        $approval = $this->designApprovalService->createApproval($request->validated());

        return $this->successResponse('Design approval created successfully', new DesignApprovalResource($approval), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $approval = $this->designApprovalService->getApprovalById($id);

        return $this->successResponse('Design approval retrieved successfully', new DesignApprovalResource($approval));
    }

    public function update(DesignApprovalRequest $request, int $id): JsonResponse
    {
        $approval = $this->designApprovalService->updateApproval($id, $request->validated());

        return $this->successResponse('Design approval updated successfully', new DesignApprovalResource($approval));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->designApprovalService->deleteApproval($id);

        return $this->successResponse('Design approval deleted successfully');
    }
}
