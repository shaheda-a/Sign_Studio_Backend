<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChecklistMasterRequest;
use App\Http\Resources\ChecklistMasterResource;
use App\Services\ChecklistMasterService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChecklistMasterController extends Controller
{
    use ApiResponseTrait;

    protected ChecklistMasterService $checklistMasterService;

    /**
     * Constructor injection.
     *
     * @param ChecklistMasterService $checklistMasterService
     */
    public function __construct(ChecklistMasterService $checklistMasterService)
    {
        $this->checklistMasterService = $checklistMasterService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['module', 'is_active']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $checklists = $this->checklistMasterService->getChecklists($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Checklists retrieved successfully',
                ChecklistMasterResource::collection($checklists)
            );
        }

        return $this->successResponse(
            'Checklists retrieved successfully',
            [
                'items' => ChecklistMasterResource::collection($checklists->items()),
                'meta'  => [
                    'current_page' => $checklists->currentPage(),
                    'last_page'    => $checklists->lastPage(),
                    'per_page'     => $checklists->perPage(),
                    'total'        => $checklists->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ChecklistMasterRequest $request
     * @return JsonResponse
     */
    public function store(ChecklistMasterRequest $request): JsonResponse
    {
        $checklist = $this->checklistMasterService->createChecklist($request->validated());

        return $this->successResponse(
            'Checklist created successfully',
            new ChecklistMasterResource($checklist),
            Response::HTTP_CREATED
        );
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $checklist = $this->checklistMasterService->getChecklistById($id);

        return $this->successResponse(
            'Checklist retrieved successfully',
            new ChecklistMasterResource($checklist)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ChecklistMasterRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ChecklistMasterRequest $request, int $id): JsonResponse
    {
        $checklist = $this->checklistMasterService->updateChecklist($id, $request->validated());

        return $this->successResponse(
            'Checklist updated successfully',
            new ChecklistMasterResource($checklist)
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $this->checklistMasterService->deleteChecklist($id);

        return $this->successResponse('Checklist deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $checklist = $this->checklistMasterService->restoreChecklist($id);

        return $this->successResponse(
            'Checklist restored successfully',
            new ChecklistMasterResource($checklist)
        );
    }
}
