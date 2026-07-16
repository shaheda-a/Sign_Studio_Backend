<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArchitectRequest;
use App\Http\Resources\ArchitectResource;
use App\Services\ArchitectService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ArchitectController extends Controller
{
    use ApiResponseTrait;

    protected ArchitectService $architectService;

    /**
     * Constructor injection.
     *
     * @param ArchitectService $architectService
     */
    public function __construct(ArchitectService $architectService)
    {
        $this->architectService = $architectService;
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

        $architects = $this->architectService->getArchitects($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Architects retrieved successfully',
                ArchitectResource::collection($architects)
            );
        }

        return $this->successResponse(
            'Architects retrieved successfully',
            [
                'items' => ArchitectResource::collection($architects->items()),
                'meta'  => [
                    'current_page' => $architects->currentPage(),
                    'last_page'    => $architects->lastPage(),
                    'per_page'     => $architects->perPage(),
                    'total'        => $architects->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ArchitectRequest $request
     * @return JsonResponse
     */
    public function store(ArchitectRequest $request): JsonResponse
    {
        $architect = $this->architectService->createArchitect($request->validated());

        return $this->successResponse(
            'Architect created successfully',
            new ArchitectResource($architect),
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
        $architect = $this->architectService->getArchitectById($id);

        return $this->successResponse(
            'Architect retrieved successfully',
            new ArchitectResource($architect)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ArchitectRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ArchitectRequest $request, int $id): JsonResponse
    {
        $architect = $this->architectService->updateArchitect($id, $request->validated());

        return $this->successResponse(
            'Architect updated successfully',
            new ArchitectResource($architect)
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
        $this->architectService->deleteArchitect($id);

        return $this->successResponse('Architect deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $architect = $this->architectService->restoreArchitect($id);

        return $this->successResponse(
            'Architect restored successfully',
            new ArchitectResource($architect)
        );
    }
}
