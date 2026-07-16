<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionStageRequest;
use App\Http\Resources\ProductionStageResource;
use App\Services\ProductionStageService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionStageController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected ProductionStageService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['production_plan_id', 'status', 'assigned_to']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Production stages retrieved', ProductionStageResource::collection($records));
        }

        return $this->successResponse('Production stages retrieved', [
            'items' => ProductionStageResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(ProductionStageRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Production stage created', new ProductionStageResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Production stage retrieved', new ProductionStageResource($this->service->find($id)));
    }

    public function update(ProductionStageRequest $request, int $id): JsonResponse
    {
        $record = $this->service->update($id, $request->validated());
        return $this->successResponse('Production stage updated', new ProductionStageResource($record));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Production stage deleted');
    }

    public function restore(int $id): JsonResponse
    {
        $record = $this->service->restore($id);
        return $this->successResponse('Production stage restored', new ProductionStageResource($record));
    }

    public function start(int $id): JsonResponse
    {
        $record = $this->service->startStage($id);
        return $this->successResponse('Production stage started', new ProductionStageResource($record));
    }

    public function complete(int $id): JsonResponse
    {
        $record = $this->service->completeStage($id);
        return $this->successResponse('Production stage completed', new ProductionStageResource($record));
    }
}
