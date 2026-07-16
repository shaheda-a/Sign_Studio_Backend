<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadSourceRequest;
use App\Http\Resources\LeadSourceResource;
use App\Services\LeadSourceService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadSourceController extends Controller
{
    use ApiResponseTrait;

    protected LeadSourceService $leadSourceService;

    /**
     * Constructor injection.
     *
     * @param LeadSourceService $leadSourceService
     */
    public function __construct(LeadSourceService $leadSourceService)
    {
        $this->leadSourceService = $leadSourceService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'is_active']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $sources = $this->leadSourceService->getLeadSources($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Lead sources retrieved successfully',
                LeadSourceResource::collection($sources)
            );
        }

        return $this->successResponse(
            'Lead sources retrieved successfully',
            [
                'items' => LeadSourceResource::collection($sources->items()),
                'meta'  => [
                    'current_page' => $sources->currentPage(),
                    'last_page'    => $sources->lastPage(),
                    'per_page'     => $sources->perPage(),
                    'total'        => $sources->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeadSourceRequest $request
     * @return JsonResponse
     */
    public function store(LeadSourceRequest $request): JsonResponse
    {
        $source = $this->leadSourceService->createLeadSource($request->validated());

        return $this->successResponse(
            'Lead source created successfully',
            new LeadSourceResource($source),
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
        $source = $this->leadSourceService->getLeadSourceById($id);

        return $this->successResponse(
            'Lead source retrieved successfully',
            new LeadSourceResource($source)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LeadSourceRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LeadSourceRequest $request, int $id): JsonResponse
    {
        $source = $this->leadSourceService->updateLeadSource($id, $request->validated());

        return $this->successResponse(
            'Lead source updated successfully',
            new LeadSourceResource($source)
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
        $this->leadSourceService->deleteLeadSource($id);

        return $this->successResponse('Lead source deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $source = $this->leadSourceService->restoreLeadSource($id);

        return $this->successResponse(
            'Lead source restored successfully',
            new LeadSourceResource($source)
        );
    }
}
