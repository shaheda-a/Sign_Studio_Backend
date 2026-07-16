<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadScoreRequest;
use App\Http\Resources\LeadScoreResource;
use App\Services\LeadScoreService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadScoreController extends Controller
{
    use ApiResponseTrait;

    protected LeadScoreService $leadScoreService;

    /**
     * Constructor injection.
     *
     * @param LeadScoreService $leadScoreService
     */
    public function __construct(LeadScoreService $leadScoreService)
    {
        $this->leadScoreService = $leadScoreService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['lead_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $scores = $this->leadScoreService->getScores($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Lead scores retrieved successfully',
                LeadScoreResource::collection($scores)
            );
        }

        return $this->successResponse(
            'Lead scores retrieved successfully',
            [
                'items' => LeadScoreResource::collection($scores->items()),
                'meta'  => [
                    'current_page' => $scores->currentPage(),
                    'last_page'    => $scores->lastPage(),
                    'per_page'     => $scores->perPage(),
                    'total'        => $scores->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeadScoreRequest $request
     * @return JsonResponse
     */
    public function store(LeadScoreRequest $request): JsonResponse
    {
        $score = $this->leadScoreService->createScore($request->validated());

        return $this->successResponse(
            'Lead score created successfully',
            new LeadScoreResource($score),
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
        $score = $this->leadScoreService->getScoreById($id);

        return $this->successResponse(
            'Lead score retrieved successfully',
            new LeadScoreResource($score)
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
        $this->leadScoreService->deleteScore($id);

        return $this->successResponse('Lead score deleted successfully');
    }
}
