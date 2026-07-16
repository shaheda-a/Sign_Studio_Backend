<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApprovalRuleRequest;
use App\Http\Resources\ApprovalRuleResource;
use App\Services\ApprovalRuleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApprovalRuleController extends Controller
{
    use ApiResponseTrait;

    protected ApprovalRuleService $approvalRuleService;

    /**
     * Constructor injection.
     *
     * @param ApprovalRuleService $approvalRuleService
     */
    public function __construct(ApprovalRuleService $approvalRuleService)
    {
        $this->approvalRuleService = $approvalRuleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['module', 'is_active']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $rules = $this->approvalRuleService->getRules($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Approval rules retrieved successfully',
                ApprovalRuleResource::collection($rules)
            );
        }

        return $this->successResponse(
            'Approval rules retrieved successfully',
            [
                'items' => ApprovalRuleResource::collection($rules->items()),
                'meta'  => [
                    'current_page' => $rules->currentPage(),
                    'last_page'    => $rules->lastPage(),
                    'per_page'     => $rules->perPage(),
                    'total'        => $rules->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApprovalRuleRequest $request
     * @return JsonResponse
     */
    public function store(ApprovalRuleRequest $request): JsonResponse
    {
        $rule = $this->approvalRuleService->createRule($request->validated());

        return $this->successResponse(
            'Approval rule created successfully',
            new ApprovalRuleResource($rule),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $rule = $this->approvalRuleService->getRuleById($id);

        return $this->successResponse(
            'Approval rule retrieved successfully',
            new ApprovalRuleResource($rule)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApprovalRuleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ApprovalRuleRequest $request, int $id): JsonResponse
    {
        $rule = $this->approvalRuleService->updateRule($id, $request->validated());

        return $this->successResponse(
            'Approval rule updated successfully',
            new ApprovalRuleResource($rule)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->approvalRuleService->deleteRule($id);

        return $this->successResponse('Approval rule deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $rule = $this->approvalRuleService->restoreRule($id);

        return $this->successResponse(
            'Approval rule restored successfully',
            new ApprovalRuleResource($rule)
        );
    }
}
