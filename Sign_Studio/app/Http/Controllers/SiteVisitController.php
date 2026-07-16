<?php

namespace App\Http\Controllers;

use App\Http\Requests\SiteVisitRequest;
use App\Http\Resources\SiteVisitResource;
use App\Services\SiteVisitService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SiteVisitController extends Controller
{
    use ApiResponseTrait;

    protected SiteVisitService $siteVisitService;

    public function __construct(SiteVisitService $siteVisitService)
    {
        $this->siteVisitService = $siteVisitService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['lead_id', 'assigned_to', 'status']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $visits = $this->siteVisitService->getSiteVisits($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Site visits retrieved successfully', SiteVisitResource::collection($visits));
        }

        return $this->successResponse('Site visits retrieved successfully', [
            'items' => SiteVisitResource::collection($visits->items()),
            'meta'  => [
                'current_page' => $visits->currentPage(),
                'last_page'    => $visits->lastPage(),
                'per_page'     => $visits->perPage(),
                'total'        => $visits->total(),
            ],
        ]);
    }

    public function store(SiteVisitRequest $request): JsonResponse
    {
        $visit = $this->siteVisitService->createSiteVisit($request->validated());

        return $this->successResponse('Site visit created successfully', new SiteVisitResource($visit), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $visit = $this->siteVisitService->getSiteVisitById($id);

        return $this->successResponse('Site visit retrieved successfully', new SiteVisitResource($visit));
    }

    public function update(SiteVisitRequest $request, int $id): JsonResponse
    {
        $visit = $this->siteVisitService->updateSiteVisit($id, $request->validated());

        return $this->successResponse('Site visit updated successfully', new SiteVisitResource($visit));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->siteVisitService->deleteSiteVisit($id);

        return $this->successResponse('Site visit deleted successfully');
    }

    public function restore(int $id): JsonResponse
    {
        $visit = $this->siteVisitService->restoreSiteVisit($id);

        return $this->successResponse('Site visit restored successfully', new SiteVisitResource($visit));
    }
}
