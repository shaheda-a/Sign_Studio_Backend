<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionPlanRequest;
use App\Http\Resources\ProductionPlanResource;
use App\Services\ProductionPlanService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionPlanController extends Controller
{
    use ApiResponseTrait;

    protected ProductionPlanService $productionPlanService;

    public function __construct(ProductionPlanService $productionPlanService)
    {
        $this->productionPlanService = $productionPlanService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['order_id', 'status']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $plans = $this->productionPlanService->getPlans($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Production plans retrieved successfully', ProductionPlanResource::collection($plans));
        }

        return $this->successResponse('Production plans retrieved successfully', [
            'items' => ProductionPlanResource::collection($plans->items()),
            'meta'  => [
                'current_page' => $plans->currentPage(),
                'last_page'    => $plans->lastPage(),
                'per_page'     => $plans->perPage(),
                'total'        => $plans->total(),
            ],
        ]);
    }

    public function store(ProductionPlanRequest $request): JsonResponse
    {
        $plan = $this->productionPlanService->createPlan($request->validated());

        return $this->successResponse('Production plan created successfully', new ProductionPlanResource($plan), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $plan = $this->productionPlanService->getPlanById($id);

        return $this->successResponse('Production plan retrieved successfully', new ProductionPlanResource($plan));
    }

    public function update(ProductionPlanRequest $request, int $id): JsonResponse
    {
        $plan = $this->productionPlanService->updatePlan($id, $request->validated());

        return $this->successResponse('Production plan updated successfully', new ProductionPlanResource($plan));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->productionPlanService->deletePlan($id);

        return $this->successResponse('Production plan deleted successfully');
    }

    public function restore(int $id): JsonResponse
    {
        $plan = $this->productionPlanService->restorePlan($id);

        return $this->successResponse('Production plan restored successfully', new ProductionPlanResource($plan));
    }

    public function qcSummary(int $id): JsonResponse
    {
        $summary = $this->productionPlanService->computeQcSummary($id);

        return $this->successResponse('QC summary computed', $summary);
    }
}
