<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskAcceptanceRequest;
use App\Http\Resources\TaskAcceptanceResource;
use App\Services\TaskAcceptanceService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskAcceptanceController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected TaskAcceptanceService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['task_id', 'user_id', 'status']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Task acceptances retrieved', TaskAcceptanceResource::collection($records));
        }

        return $this->successResponse('Task acceptances retrieved', [
            'items' => TaskAcceptanceResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(TaskAcceptanceRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Task acceptance recorded', new TaskAcceptanceResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Task acceptance retrieved', new TaskAcceptanceResource($this->service->find($id)));
    }
}
