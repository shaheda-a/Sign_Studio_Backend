<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionDelayRequest;
use App\Http\Resources\ProductionDelayResource;
use App\Services\ProductionDelayService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionDelayController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected ProductionDelayService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['production_plan_id', 'stage_id']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Production delays retrieved', ProductionDelayResource::collection($records));
        }

        return $this->successResponse('Production delays retrieved', [
            'items' => ProductionDelayResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(ProductionDelayRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Production delay recorded', new ProductionDelayResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Production delay retrieved', new ProductionDelayResource($this->service->find($id)));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Production delay deleted');
    }
}
