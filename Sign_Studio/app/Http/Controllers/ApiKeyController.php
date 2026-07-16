<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApiKeyRequest;
use App\Http\Resources\ApiKeyResource;
use App\Services\ApiKeyService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyController extends Controller
{
    use ApiResponseTrait;

    protected ApiKeyService $apiKeyService;

    /**
     * Constructor injection.
     *
     * @param ApiKeyService $apiKeyService
     */
    public function __construct(ApiKeyService $apiKeyService)
    {
        $this->apiKeyService = $apiKeyService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['is_active']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $keys = $this->apiKeyService->getKeys($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'API keys retrieved successfully',
                ApiKeyResource::collection($keys)
            );
        }

        return $this->successResponse(
            'API keys retrieved successfully',
            [
                'items' => ApiKeyResource::collection($keys->items()),
                'meta'  => [
                    'current_page' => $keys->currentPage(),
                    'last_page'    => $keys->lastPage(),
                    'per_page'     => $keys->perPage(),
                    'total'        => $keys->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ApiKeyRequest $request
     * @return JsonResponse
     */
    public function store(ApiKeyRequest $request): JsonResponse
    {
        $key = $this->apiKeyService->createKey($request->validated());

        return $this->successResponse(
            'API key created successfully',
            new ApiKeyResource($key),
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
        $key = $this->apiKeyService->getKeyById($id);

        return $this->successResponse(
            'API key retrieved successfully',
            new ApiKeyResource($key)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ApiKeyRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(ApiKeyRequest $request, int $id): JsonResponse
    {
        $key = $this->apiKeyService->updateKey($id, $request->validated());

        return $this->successResponse(
            'API key updated successfully',
            new ApiKeyResource($key)
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
        $this->apiKeyService->deleteKey($id);

        return $this->successResponse('API key deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $key = $this->apiKeyService->restoreKey($id);

        return $this->successResponse(
            'API key restored successfully',
            new ApiKeyResource($key)
        );
    }
}
