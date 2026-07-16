<?php

namespace App\Http\Controllers;

use App\Http\Requests\BranchRequest;
use App\Http\Resources\BranchResource;
use App\Services\BranchService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchController extends Controller
{
    use ApiResponseTrait;

    protected BranchService $branchService;

    /**
     * Constructor injection for BranchService.
     *
     * @param BranchService $branchService
     */
    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'is_active', 'with_trashed']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $branches = $this->branchService->getBranches($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Branches retrieved successfully',
                BranchResource::collection($branches)
            );
        }

        return $this->successResponse(
            'Branches retrieved successfully',
            [
                'items' => BranchResource::collection($branches->items()),
                'meta'  => [
                    'current_page' => $branches->currentPage(),
                    'last_page'    => $branches->lastPage(),
                    'per_page'     => $branches->perPage(),
                    'total'        => $branches->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BranchRequest $request
     * @return JsonResponse
     */
    public function store(BranchRequest $request): JsonResponse
    {
        $branch = $this->branchService->createBranch($request->validated());

        return $this->successResponse(
            'Branch created successfully',
            new BranchResource($branch),
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
        $branch = $this->branchService->getBranchById($id);

        return $this->successResponse(
            'Branch retrieved successfully',
            new BranchResource($branch)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BranchRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(BranchRequest $request, int $id): JsonResponse
    {
        $branch = $this->branchService->updateBranch($id, $request->validated());

        return $this->successResponse(
            'Branch updated successfully',
            new BranchResource($branch)
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
        $this->branchService->deleteBranch($id);

        return $this->successResponse('Branch deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $branch = $this->branchService->restoreBranch($id);

        return $this->successResponse(
            'Branch restored successfully',
            new BranchResource($branch)
        );
    }
}
