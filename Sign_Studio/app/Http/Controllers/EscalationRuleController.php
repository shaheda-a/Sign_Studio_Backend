<?php

namespace App\Http\Controllers;

use App\Http\Requests\EscalationRuleRequest;
use App\Http\Resources\EscalationRuleResource;
use App\Services\EscalationRuleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EscalationRuleController extends Controller
{
    use ApiResponseTrait;

    protected EscalationRuleService $escalationRuleService;

    /**
     * Constructor injection.
     *
     * @param EscalationRuleService $escalationRuleService
     */
    public function __construct(EscalationRuleService $escalationRuleService)
    {
        $this->escalationRuleService = $escalationRuleService;
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

        $rules = $this->escalationRuleService->getRules($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Escalation rules retrieved successfully',
                EscalationRuleResource::collection($rules)
            );
        }

        return $this->successResponse(
            'Escalation rules retrieved successfully',
            [
                'items' => EscalationRuleResource::collection($rules->items()),
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
     * @param EscalationRuleRequest $request
     * @return JsonResponse
     */
    public function store(EscalationRuleRequest $request): JsonResponse
    {
        $rule = $this->escalationRuleService->createRule($request->validated());

        return $this->successResponse(
            'Escalation rule created successfully',
            new EscalationRuleResource($rule),
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
        $rule = $this->escalationRuleService->getRuleById($id);

        return $this->successResponse(
            'Escalation rule retrieved successfully',
            new EscalationRuleResource($rule)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param EscalationRuleRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(EscalationRuleRequest $request, int $id): JsonResponse
    {
        $rule = $this->escalationRuleService->updateRule($id, $request->validated());

        return $this->successResponse(
            'Escalation rule updated successfully',
            new EscalationRuleResource($rule)
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
        $this->escalationRuleService->deleteRule($id);

        return $this->successResponse('Escalation rule deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $rule = $this->escalationRuleService->restoreRule($id);

        return $this->successResponse(
            'Escalation rule restored successfully',
            new EscalationRuleResource($rule)
        );
    }
}
