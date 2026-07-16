<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Services\TaskService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskController extends Controller
{
    use ApiResponseTrait;

    protected TaskService $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['order_id', 'assigned_to', 'department_id', 'status']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $tasks = $this->taskService->getTasks($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Tasks retrieved successfully', TaskResource::collection($tasks));
        }

        return $this->successResponse('Tasks retrieved successfully', [
            'items' => TaskResource::collection($tasks->items()),
            'meta'  => [
                'current_page' => $tasks->currentPage(),
                'last_page'    => $tasks->lastPage(),
                'per_page'     => $tasks->perPage(),
                'total'        => $tasks->total(),
            ],
        ]);
    }

    public function store(TaskRequest $request): JsonResponse
    {
        $task = $this->taskService->createTask($request->validated());

        return $this->successResponse('Task created successfully', new TaskResource($task), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $task = $this->taskService->getTaskById($id);

        return $this->successResponse('Task retrieved successfully', new TaskResource($task));
    }

    public function update(TaskRequest $request, int $id): JsonResponse
    {
        $task = $this->taskService->updateTask($id, $request->validated());

        return $this->successResponse('Task updated successfully', new TaskResource($task));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->taskService->deleteTask($id);

        return $this->successResponse('Task deleted successfully');
    }

    public function restore(int $id): JsonResponse
    {
        $task = $this->taskService->restoreTask($id);

        return $this->successResponse('Task restored successfully', new TaskResource($task));
    }

    public function start(int $id): JsonResponse
    {
        $task = $this->taskService->startTask($id);

        return $this->successResponse('Task started', new TaskResource($task));
    }

    public function complete(int $id): JsonResponse
    {
        $task = $this->taskService->completeTask($id);

        return $this->successResponse('Task completed', new TaskResource($task));
    }
}
