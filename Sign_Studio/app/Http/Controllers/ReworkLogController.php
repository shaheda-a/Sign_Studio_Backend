<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReworkLogRequest;
use App\Http\Resources\ReworkLogResource;
use App\Services\ReworkLogService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ReworkLogController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected ReworkLogService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['production_plan_id', 'stage_id', 'status']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Rework logs retrieved', ReworkLogResource::collection($records));
        }

        return $this->successResponse('Rework logs retrieved', [
            'items' => ReworkLogResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(ReworkLogRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Rework log created', new ReworkLogResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Rework log retrieved', new ReworkLogResource($this->service->find($id)));
    }

    public function update(ReworkLogRequest $request, int $id): JsonResponse
    {
        $record = $this->service->update($id, $request->validated());
        return $this->successResponse('Rework log updated', new ReworkLogResource($record));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Rework log deleted');
    }

    public function restore(int $id): JsonResponse
    {
        $record = $this->service->restore($id);
        return $this->successResponse('Rework log restored', new ReworkLogResource($record));
    }
}
