<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskPauseLogRequest;
use App\Http\Resources\TaskPauseLogResource;
use App\Services\TaskPauseLogService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskPauseLogController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected TaskPauseLogService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['task_id']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Pause logs retrieved', TaskPauseLogResource::collection($records));
        }

        return $this->successResponse('Pause logs retrieved', [
            'items' => TaskPauseLogResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(TaskPauseLogRequest $request): JsonResponse
    {
        $record = $this->service->pauseTask($request->validated());
        return $this->successResponse('Task paused', new TaskPauseLogResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Pause log retrieved', new TaskPauseLogResource($this->service->find($id)));
    }

    public function resume(int $id): JsonResponse
    {
        $record = $this->service->resumeTask($id);
        return $this->successResponse('Task resumed', new TaskPauseLogResource($record));
    }
}
