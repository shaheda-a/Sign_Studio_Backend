<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadActivityRequest;
use App\Http\Resources\LeadActivityResource;
use App\Services\LeadActivityService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadActivityController extends Controller
{
    use ApiResponseTrait;

    protected LeadActivityService $leadActivityService;

    /**
     * Constructor injection.
     *
     * @param LeadActivityService $leadActivityService
     */
    public function __construct(LeadActivityService $leadActivityService)
    {
        $this->leadActivityService = $leadActivityService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['lead_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $activities = $this->leadActivityService->getActivities($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Lead activities retrieved successfully',
                LeadActivityResource::collection($activities)
            );
        }

        return $this->successResponse(
            'Lead activities retrieved successfully',
            [
                'items' => LeadActivityResource::collection($activities->items()),
                'meta'  => [
                    'current_page' => $activities->currentPage(),
                    'last_page'    => $activities->lastPage(),
                    'per_page'     => $activities->perPage(),
                    'total'        => $activities->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeadActivityRequest $request
     * @return JsonResponse
     */
    public function store(LeadActivityRequest $request): JsonResponse
    {
        $activity = $this->leadActivityService->createActivity($request->validated());

        return $this->successResponse(
            'Lead activity created successfully',
            new LeadActivityResource($activity),
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
        $activity = $this->leadActivityService->getActivityById($id);

        return $this->successResponse(
            'Lead activity retrieved successfully',
            new LeadActivityResource($activity)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LeadActivityRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LeadActivityRequest $request, int $id): JsonResponse
    {
        $activity = $this->leadActivityService->updateActivity($id, $request->validated());

        return $this->successResponse(
            'Lead activity updated successfully',
            new LeadActivityResource($activity)
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
        $this->leadActivityService->deleteActivity($id);

        return $this->successResponse('Lead activity deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $activity = $this->leadActivityService->restoreActivity($id);

        return $this->successResponse(
            'Lead activity restored successfully',
            new LeadActivityResource($activity)
        );
    }
}
