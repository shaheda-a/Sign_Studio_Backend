<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductionProofRequest;
use App\Http\Resources\ProductionProofResource;
use App\Services\ProductionProofService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductionProofController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected ProductionProofService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['production_plan_id', 'stage_id']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Production proofs retrieved', ProductionProofResource::collection($records));
        }

        return $this->successResponse('Production proofs retrieved', [
            'items' => ProductionProofResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(ProductionProofRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Production proof uploaded', new ProductionProofResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Production proof retrieved', new ProductionProofResource($this->service->find($id)));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Production proof deleted');
    }

    public function restore(int $id): JsonResponse
    {
        $record = $this->service->restore($id);
        return $this->successResponse('Production proof restored', new ProductionProofResource($record));
    }
}
