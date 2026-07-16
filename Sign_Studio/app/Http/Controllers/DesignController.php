<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesignRequest;
use App\Http\Resources\DesignResource;
use App\Services\DesignService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DesignController extends Controller
{
    use ApiResponseTrait;

    protected DesignService $designService;

    public function __construct(DesignService $designService)
    {
        $this->designService = $designService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['lead_id', 'assigned_to', 'status']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $designs = $this->designService->getDesigns($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Designs retrieved successfully', DesignResource::collection($designs));
        }

        return $this->successResponse('Designs retrieved successfully', [
            'items' => DesignResource::collection($designs->items()),
            'meta'  => [
                'current_page' => $designs->currentPage(),
                'last_page'    => $designs->lastPage(),
                'per_page'     => $designs->perPage(),
                'total'        => $designs->total(),
            ],
        ]);
    }

    public function store(DesignRequest $request): JsonResponse
    {
        $design = $this->designService->createDesign($request->validated());

        return $this->successResponse('Design created successfully', new DesignResource($design), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $design = $this->designService->getDesignById($id);

        return $this->successResponse('Design retrieved successfully', new DesignResource($design));
    }

    public function update(DesignRequest $request, int $id): JsonResponse
    {
        $design = $this->designService->updateDesign($id, $request->validated());

        return $this->successResponse('Design updated successfully', new DesignResource($design));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->designService->deleteDesign($id);

        return $this->successResponse('Design deleted successfully');
    }

    public function restore(int $id): JsonResponse
    {
        $design = $this->designService->restoreDesign($id);

        return $this->successResponse('Design restored successfully', new DesignResource($design));
    }

    public function lock(int $id): JsonResponse
    {
        $design = $this->designService->lockDesign($id);

        return $this->successResponse('Design locked successfully', new DesignResource($design));
    }
}
