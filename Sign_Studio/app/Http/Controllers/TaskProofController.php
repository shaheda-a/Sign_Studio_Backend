<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskProofRequest;
use App\Http\Resources\TaskProofResource;
use App\Services\TaskProofService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskProofController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected TaskProofService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['task_id']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Task proofs retrieved', TaskProofResource::collection($records));
        }

        return $this->successResponse('Task proofs retrieved', [
            'items' => TaskProofResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(TaskProofRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('Task proof uploaded', new TaskProofResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('Task proof retrieved', new TaskProofResource($this->service->find($id)));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('Task proof deleted');
    }

    public function restore(int $id): JsonResponse
    {
        $record = $this->service->restore($id);
        return $this->successResponse('Task proof restored', new TaskProofResource($record));
    }
}
