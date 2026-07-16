<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskBottleneckRequest;
use App\Http\Resources\TaskBottleneckResource;
use App\Services\TaskBottleneckService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskBottleneckController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected TaskBottleneckService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['task_id']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Task bottlenecks retrieved', TaskBottleneckResource::collection($records));
        }

        return $this->successResponse('Task bottlenecks retrieved', [
            'items' => TaskBottleneckResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(TaskBottleneckRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Bottleneck recorded', new TaskBottleneckResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Bottleneck retrieved', new TaskBottleneckResource($this->service->find($id)));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Bottleneck deleted');
    }

    public function resolve(int $id): JsonResponse
    {
        $record = $this->service->resolve($id);
        return $this->successResponse('Bottleneck resolved', new TaskBottleneckResource($record));
    }
}
