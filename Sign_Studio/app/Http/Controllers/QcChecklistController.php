<?php

namespace App\Http\Controllers;

use App\Http\Requests\QcChecklistRequest;
use App\Http\Resources\QcChecklistResource;
use App\Services\QcChecklistService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QcChecklistController extends Controller
{
    use ApiResponseTrait;

    public function __construct(protected QcChecklistService $service) {}

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['production_plan_id', 'is_passed', 'rework_required']);
        $perPage = ($request->query('per_page', 15) == -1) ? -1 : (int) $request->query('per_page', 15);
        $records = $this->service->getAll($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('QC checklists retrieved', QcChecklistResource::collection($records));
        }

        return $this->successResponse('QC checklists retrieved', [
            'items' => QcChecklistResource::collection($records->items()),
            'meta'  => ['current_page' => $records->currentPage(), 'last_page' => $records->lastPage(), 'per_page' => $records->perPage(), 'total' => $records->total()],
        ]);
    }

    public function store(QcChecklistRequest $request): JsonResponse
    {
        $record = $this->service->create($request->validated());
        return $this->successResponse('QC checklist item created', new QcChecklistResource($record), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        return $this->successResponse('QC checklist item retrieved', new QcChecklistResource($this->service->find($id)));
    }

    public function update(QcChecklistRequest $request, int $id): JsonResponse
    {
        $record = $this->service->update($id, $request->validated());
        return $this->successResponse('QC checklist item updated', new QcChecklistResource($record));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->service->delete($id);
        return $this->successResponse('QC checklist item deleted');
    }

    public function restore(int $id): JsonResponse
    {
        $record = $this->service->restore($id);
        return $this->successResponse('QC checklist item restored', new QcChecklistResource($record));
    }
}
