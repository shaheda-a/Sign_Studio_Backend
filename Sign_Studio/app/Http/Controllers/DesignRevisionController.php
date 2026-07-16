<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesignRevisionRequest;
use App\Http\Resources\DesignRevisionResource;
use App\Services\DesignRevisionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DesignRevisionController extends Controller
{
    use ApiResponseTrait;

    protected DesignRevisionService $designRevisionService;

    public function __construct(DesignRevisionService $designRevisionService)
    {
        $this->designRevisionService = $designRevisionService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['design_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $revisions = $this->designRevisionService->getRevisions($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Design revisions retrieved successfully', DesignRevisionResource::collection($revisions));
        }

        return $this->successResponse('Design revisions retrieved successfully', [
            'items' => DesignRevisionResource::collection($revisions->items()),
            'meta'  => [
                'current_page' => $revisions->currentPage(),
                'last_page'    => $revisions->lastPage(),
                'per_page'     => $revisions->perPage(),
                'total'        => $revisions->total(),
            ],
        ]);
    }

    public function store(DesignRevisionRequest $request): JsonResponse
    {
        $revision = $this->designRevisionService->createRevision($request->validated());

        return $this->successResponse('Design revision created successfully', new DesignRevisionResource($revision), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $revision = $this->designRevisionService->getRevisionById($id);

        return $this->successResponse('Design revision retrieved successfully', new DesignRevisionResource($revision));
    }

    public function update(DesignRevisionRequest $request, int $id): JsonResponse
    {
        $revision = $this->designRevisionService->updateRevision($id, $request->validated());

        return $this->successResponse('Design revision updated successfully', new DesignRevisionResource($revision));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->designRevisionService->deleteRevision($id);

        return $this->successResponse('Design revision deleted successfully');
    }

    public function restore(int $id): JsonResponse
    {
        $revision = $this->designRevisionService->restoreRevision($id);

        return $this->successResponse('Design revision restored successfully', new DesignRevisionResource($revision));
    }
}
