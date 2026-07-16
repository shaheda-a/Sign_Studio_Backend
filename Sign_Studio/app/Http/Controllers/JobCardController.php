<?php

namespace App\Http\Controllers;

use App\Http\Requests\JobCardRequest;
use App\Http\Resources\JobCardResource;
use App\Services\JobCardService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JobCardController extends Controller
{
    use ApiResponseTrait;

    protected JobCardService $jobCardService;

    public function __construct(JobCardService $jobCardService)
    {
        $this->jobCardService = $jobCardService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['order_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $jobCards = $this->jobCardService->getJobCards($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Job cards retrieved successfully', JobCardResource::collection($jobCards));
        }

        return $this->successResponse('Job cards retrieved successfully', [
            'items' => JobCardResource::collection($jobCards->items()),
            'meta'  => [
                'current_page' => $jobCards->currentPage(),
                'last_page'    => $jobCards->lastPage(),
                'per_page'     => $jobCards->perPage(),
                'total'        => $jobCards->total(),
            ],
        ]);
    }

    public function store(JobCardRequest $request): JsonResponse
    {
        $jobCard = $this->jobCardService->createJobCard($request->validated());

        return $this->successResponse('Job card created successfully', new JobCardResource($jobCard), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $jobCard = $this->jobCardService->getJobCardById($id);

        return $this->successResponse('Job card retrieved successfully', new JobCardResource($jobCard));
    }

    public function update(JobCardRequest $request, int $id): JsonResponse
    {
        $jobCard = $this->jobCardService->updateJobCard($id, $request->validated());

        return $this->successResponse('Job card updated successfully', new JobCardResource($jobCard));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->jobCardService->deleteJobCard($id);

        return $this->successResponse('Job card deleted successfully');
    }

    public function restore(int $id): JsonResponse
    {
        $jobCard = $this->jobCardService->restoreJobCard($id);

        return $this->successResponse('Job card restored successfully', new JobCardResource($jobCard));
    }

    public function lockScope(int $id): JsonResponse
    {
        $jobCard = $this->jobCardService->lockScope($id);

        return $this->successResponse('Job card scope locked', new JobCardResource($jobCard));
    }
}
