<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskDelayRequest;
use App\Http\Resources\TaskDelayResource;
use App\Services\TaskDelayService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskDelayController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected TaskDelayService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['task_id']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Task delays retrieved', TaskDelayResource::collection($records));
        }

        return $this->successResponse('Task delays retrieved', [
            'items' => TaskDelayResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(TaskDelayRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Task delay recorded', new TaskDelayResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Task delay retrieved', new TaskDelayResource($this->service->find($id)));
    }

    public function update(TaskDelayRequest $request, int $id): JsonResponse
    {
        $record = $this->service->update($id, $request->validated());
        return $this->successResponse('Task delay updated', new TaskDelayResource($record));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Task delay deleted');
    }
}
