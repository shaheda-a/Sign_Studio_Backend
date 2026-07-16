<?php

namespace App\Http\Controllers;

use App\Http\Requests\PipelineStageRequest;
use App\Http\Resources\PipelineStageResource;
use App\Services\PipelineStageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PipelineStageController extends Controller
{
    use ApiResponseTrait;

    protected PipelineStageService $pipelineStageService;

    /**
     * Constructor injection.
     *
     * @param PipelineStageService $pipelineStageService
     */
    public function __construct(PipelineStageService $pipelineStageService)
    {
        $this->pipelineStageService = $pipelineStageService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['is_active']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $stages = $this->pipelineStageService->getPipelineStages($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Pipeline stages retrieved successfully',
                PipelineStageResource::collection($stages)
            );
        }

        return $this->successResponse(
            'Pipeline stages retrieved successfully',
            [
                'items' => PipelineStageResource::collection($stages->items()),
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
     * @param PipelineStageRequest $request
     * @return JsonResponse
     */
    public function store(PipelineStageRequest $request): JsonResponse
    {
        $stage = $this->pipelineStageService->createPipelineStage($request->validated());

        return $this->successResponse(
            'Pipeline stage created successfully',
            new PipelineStageResource($stage),
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
        $stage = $this->pipelineStageService->getPipelineStageById($id);

        return $this->successResponse(
            'Pipeline stage retrieved successfully',
            new PipelineStageResource($stage)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PipelineStageRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(PipelineStageRequest $request, int $id): JsonResponse
    {
        $stage = $this->pipelineStageService->updatePipelineStage($id, $request->validated());

        return $this->successResponse(
            'Pipeline stage updated successfully',
            new PipelineStageResource($stage)
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
        $this->pipelineStageService->deletePipelineStage($id);

        return $this->successResponse('Pipeline stage deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $stage = $this->pipelineStageService->restorePipelineStage($id);

        return $this->successResponse(
            'Pipeline stage restored successfully',
            new PipelineStageResource($stage)
        );
    }
}
