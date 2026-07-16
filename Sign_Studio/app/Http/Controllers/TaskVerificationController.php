<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskVerificationRequest;
use App\Http\Resources\TaskVerificationResource;
use App\Services\TaskVerificationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskVerificationController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected TaskVerificationService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['task_id', 'status']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Task verifications retrieved', TaskVerificationResource::collection($records));
        }

        return $this->successResponse('Task verifications retrieved', [
            'items' => TaskVerificationResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(TaskVerificationRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Task verification recorded', new TaskVerificationResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Task verification retrieved', new TaskVerificationResource($this->service->find($id)));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Task verification deleted');
    }
}
