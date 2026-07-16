<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadFollowupRequest;
use App\Http\Resources\LeadFollowupResource;
use App\Services\LeadFollowupService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadFollowupController extends Controller
{
    use ApiResponseTrait;

    protected LeadFollowupService $leadFollowupService;

    /**
     * Constructor injection.
     *
     * @param LeadFollowupService $leadFollowupService
     */
    public function __construct(LeadFollowupService $leadFollowupService)
    {
        $this->leadFollowupService = $leadFollowupService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['lead_id', 'status']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $followups = $this->leadFollowupService->getFollowups($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Lead followups retrieved successfully',
                LeadFollowupResource::collection($followups)
            );
        }

        return $this->successResponse(
            'Lead followups retrieved successfully',
            [
                'items' => LeadFollowupResource::collection($followups->items()),
                'meta'  => [
                    'current_page' => $followups->currentPage(),
                    'last_page'    => $followups->lastPage(),
                    'per_page'     => $followups->perPage(),
                    'total'        => $followups->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeadFollowupRequest $request
     * @return JsonResponse
     */
    public function store(LeadFollowupRequest $request): JsonResponse
    {
        $followup = $this->leadFollowupService->createFollowup($request->validated());

        return $this->successResponse(
            'Lead followup created successfully',
            new LeadFollowupResource($followup),
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
        $followup = $this->leadFollowupService->getFollowupById($id);

        return $this->successResponse(
            'Lead followup retrieved successfully',
            new LeadFollowupResource($followup)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LeadFollowupRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LeadFollowupRequest $request, int $id): JsonResponse
    {
        $followup = $this->leadFollowupService->updateFollowup($id, $request->validated());

        return $this->successResponse(
            'Lead followup updated successfully',
            new LeadFollowupResource($followup)
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
        $this->leadFollowupService->deleteFollowup($id);

        return $this->successResponse('Lead followup deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $followup = $this->leadFollowupService->restoreFollowup($id);

        return $this->successResponse(
            'Lead followup restored successfully',
            new LeadFollowupResource($followup)
        );
    }
}
