<?php

namespace App\Http\Controllers;

use App\Http\Requests\StatusMasterRequest;
use App\Http\Resources\StatusMasterResource;
use App\Services\StatusMasterService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StatusMasterController extends Controller
{
    use ApiResponseTrait;

    protected StatusMasterService $statusMasterService;

    /**
     * Constructor injection.
     *
     * @param StatusMasterService $statusMasterService
     */
    public function __construct(StatusMasterService $statusMasterService)
    {
        $this->statusMasterService = $statusMasterService;
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

        $statuses = $this->statusMasterService->getStatuses($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Statuses retrieved successfully',
                StatusMasterResource::collection($statuses)
            );
        }

        return $this->successResponse(
            'Statuses retrieved successfully',
            [
                'items' => StatusMasterResource::collection($statuses->items()),
                'meta'  => [
                    'current_page' => $statuses->currentPage(),
                    'last_page'    => $statuses->lastPage(),
                    'per_page'     => $statuses->perPage(),
                    'total'        => $statuses->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StatusMasterRequest $request
     * @return JsonResponse
     */
    public function store(StatusMasterRequest $request): JsonResponse
    {
        $status = $this->statusMasterService->createStatus($request->validated());

        return $this->successResponse(
            'Status created successfully',
            new StatusMasterResource($status),
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
        $status = $this->statusMasterService->getStatusById($id);

        return $this->successResponse(
            'Status retrieved successfully',
            new StatusMasterResource($status)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param StatusMasterRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(StatusMasterRequest $request, int $id): JsonResponse
    {
        $status = $this->statusMasterService->updateStatus($id, $request->validated());

        return $this->successResponse(
            'Status updated successfully',
            new StatusMasterResource($status)
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
        $this->statusMasterService->deleteStatus($id);

        return $this->successResponse('Status deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $status = $this->statusMasterService->restoreStatus($id);

        return $this->successResponse(
            'Status restored successfully',
            new StatusMasterResource($status)
        );
    }
}
