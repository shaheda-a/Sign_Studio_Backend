<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use App\Services\DepartmentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DepartmentController extends Controller
{
    use ApiResponseTrait;

    protected DepartmentService $departmentService;

    /**
     * Constructor injection for DepartmentService.
     *
     * @param DepartmentService $departmentService
     */
    public function __construct(DepartmentService $departmentService)
    {
        $this->departmentService = $departmentService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'branch_id', 'is_active', 'with_trashed']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $departments = $this->departmentService->getDepartments($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Departments retrieved successfully',
                DepartmentResource::collection($departments)
            );
        }

        return $this->successResponse(
            'Departments retrieved successfully',
            [
                'items' => DepartmentResource::collection($departments->items()),
                'meta'  => [
                    'current_page' => $departments->currentPage(),
                    'last_page'    => $departments->lastPage(),
                    'per_page'     => $departments->perPage(),
                    'total'        => $departments->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param DepartmentRequest $request
     * @return JsonResponse
     */
    public function store(DepartmentRequest $request): JsonResponse
    {
        $department = $this->departmentService->createDepartment($request->validated());

        return $this->successResponse(
            'Department created successfully',
            new DepartmentResource($department),
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
        $department = $this->departmentService->getDepartmentById($id);

        return $this->successResponse(
            'Department retrieved successfully',
            new DepartmentResource($department)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param DepartmentRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(DepartmentRequest $request, int $id): JsonResponse
    {
        $department = $this->departmentService->updateDepartment($id, $request->validated());

        return $this->successResponse(
            'Department updated successfully',
            new DepartmentResource($department)
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
        $this->departmentService->deleteDepartment($id);

        return $this->successResponse('Department deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $department = $this->departmentService->restoreDepartment($id);

        return $this->successResponse(
            'Department restored successfully',
            new DepartmentResource($department)
        );
    }
}
