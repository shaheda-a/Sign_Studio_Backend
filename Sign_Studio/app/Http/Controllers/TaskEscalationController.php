<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskEscalationRequest;
use App\Http\Resources\TaskEscalationResource;
use App\Services\TaskEscalationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskEscalationController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected TaskEscalationService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['task_id', 'status']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Task escalations retrieved', TaskEscalationResource::collection($records));
        }

        return $this->successResponse('Task escalations retrieved', [
            'items' => TaskEscalationResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(TaskEscalationRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Task escalated', new TaskEscalationResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Task escalation retrieved', new TaskEscalationResource($this->service->find($id)));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Task escalation deleted');
    }

    public function resolve(int $id): JsonResponse
    {
        $record = $this->service->resolve($id);
        return $this->successResponse('Escalation resolved', new TaskEscalationResource($record));
    }
}
