<?php

namespace App\Http\Controllers;

use App\Http\Requests\StageMasterRequest;
use App\Http\Resources\StageMasterResource;
use App\Services\StageMasterService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StageMasterController extends Controller
{
    use ApiResponseTrait;

    protected StageMasterService $stageMasterService;

    /**
     * Constructor injection.
     *
     * @param StageMasterService $stageMasterService
     */
    public function __construct(StageMasterService $stageMasterService)
    {
        $this->stageMasterService = $stageMasterService;
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

        $stages = $this->stageMasterService->getStages($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Stages retrieved successfully',
                StageMasterResource::collection($stages)
            );
        }

        return $this->successResponse(
            'Stages retrieved successfully',
            [
                'items' => StageMasterResource::collection($stages->items()),
                'meta'  => [
                    'current_page' => $stages->currentPage(),
                    'last_page'    => $stages->lastPage(),
                    'per_page'     => $stages->perPage(),
                    'total'        => $stages->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StageMasterRequest $request
     * @return JsonResponse
     */
    public function store(StageMasterRequest $request): JsonResponse
    {
        $stage = $this->stageMasterService->createStage($request->validated());

        return $this->successResponse(
            'Stage created successfully',
            new StageMasterResource($stage),
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
        $stage = $this->stageMasterService->getStageById($id);

        return $this->successResponse(
            'Stage retrieved successfully',
            new StageMasterResource($stage)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StageMasterRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StageMasterRequest $request, int $id): JsonResponse
    {
        $stage = $this->stageMasterService->updateStage($id, $request->validated());

        return $this->successResponse(
            'Stage updated successfully',
            new StageMasterResource($stage)
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
        $this->stageMasterService->deleteStage($id);

        return $this->successResponse('Stage deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $stage = $this->stageMasterService->restoreStage($id);

        return $this->successResponse(
            'Stage restored successfully',
            new StageMasterResource($stage)
        );
    }
}
