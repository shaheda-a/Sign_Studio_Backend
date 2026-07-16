<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionScoreRequest;
use App\Http\Resources\ProductionScoreResource;
use App\Services\ProductionScoreService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionScoreController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected ProductionScoreService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['production_plan_id']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Production scores retrieved', ProductionScoreResource::collection($records));
        }

        return $this->successResponse('Production scores retrieved', [
            'items' => ProductionScoreResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(ProductionScoreRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Production score recorded', new ProductionScoreResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Production score retrieved', new ProductionScoreResource($this->service->find($id)));
    }

    public function update(ProductionScoreRequest $request, int $id): JsonResponse
    {
        $record = $this->service->update($id, $request->validated());
        return $this->successResponse('Production score updated', new ProductionScoreResource($record));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Production score deleted');
    }
}
