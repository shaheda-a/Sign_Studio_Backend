<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContractorRequest;
use App\Http\Resources\ContractorResource;
use App\Services\ContractorService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ContractorController extends Controller
{
    use ApiResponseTrait;

    protected ContractorService $contractorService;

    /**
     * Constructor injection.
     *
     * @param ContractorService $contractorService
     */
    public function __construct(ContractorService $contractorService)
    {
        $this->contractorService = $contractorService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'is_active']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $contractors = $this->contractorService->getContractors($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Contractors retrieved successfully',
                ContractorResource::collection($contractors)
            );
        }

        return $this->successResponse(
            'Contractors retrieved successfully',
            [
                'items' => ContractorResource::collection($contractors->items()),
                'meta'  => [
                    'current_page' => $contractors->currentPage(),
                    'last_page'    => $contractors->lastPage(),
                    'per_page'     => $contractors->perPage(),
                    'total'        => $contractors->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ContractorRequest $request
     * @return JsonResponse
     */
    public function store(ContractorRequest $request): JsonResponse
    {
        $contractor = $this->contractorService->createContractor($request->validated());

        return $this->successResponse(
            'Contractor created successfully',
            new ContractorResource($contractor),
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
        $contractor = $this->contractorService->getContractorById($id);

        return $this->successResponse(
            'Contractor retrieved successfully',
            new ContractorResource($contractor)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ContractorRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ContractorRequest $request, int $id): JsonResponse
    {
        $contractor = $this->contractorService->updateContractor($id, $request->validated());

        return $this->successResponse(
            'Contractor updated successfully',
            new ContractorResource($contractor)
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
        $this->contractorService->deleteContractor($id);

        return $this->successResponse('Contractor deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $contractor = $this->contractorService->restoreContractor($id);

        return $this->successResponse(
            'Contractor restored successfully',
            new ContractorResource($contractor)
        );
    }
}
