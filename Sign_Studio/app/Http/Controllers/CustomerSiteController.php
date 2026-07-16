<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerSiteRequest;
use App\Http\Resources\CustomerSiteResource;
use App\Services\CustomerSiteService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerSiteController extends Controller
{
    use ApiResponseTrait;

    protected CustomerSiteService $customerSiteService;

    /**
     * Constructor injection.
     *
     * @param CustomerSiteService $customerSiteService
     */
    public function __construct(CustomerSiteService $customerSiteService)
    {
        $this->customerSiteService = $customerSiteService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['customer_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $sites = $this->customerSiteService->getSites($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Customer sites retrieved successfully',
                CustomerSiteResource::collection($sites)
            );
        }

        return $this->successResponse(
            'Customer sites retrieved successfully',
            [
                'items' => CustomerSiteResource::collection($sites->items()),
                'meta'  => [
                    'current_page' => $sites->currentPage(),
                    'last_page'    => $sites->lastPage(),
                    'per_page'     => $sites->perPage(),
                    'total'        => $sites->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerSiteRequest $request
     * @return JsonResponse
     */
    public function store(CustomerSiteRequest $request): JsonResponse
    {
        $site = $this->customerSiteService->createSite($request->validated());

        return $this->successResponse(
            'Customer site created successfully',
            new CustomerSiteResource($site),
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
        $site = $this->customerSiteService->getSiteById($id);

        return $this->successResponse(
            'Customer site retrieved successfully',
            new CustomerSiteResource($site)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CustomerSiteRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CustomerSiteRequest $request, int $id): JsonResponse
    {
        $site = $this->customerSiteService->updateSite($id, $request->validated());

        return $this->successResponse(
            'Customer site updated successfully',
            new CustomerSiteResource($site)
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
        $this->customerSiteService->deleteSite($id);

        return $this->successResponse('Customer site deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $site = $this->customerSiteService->restoreSite($id);

        return $this->successResponse(
            'Customer site restored successfully',
            new CustomerSiteResource($site)
        );
    }
}
