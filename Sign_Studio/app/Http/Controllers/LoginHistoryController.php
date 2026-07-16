<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginHistoryResource;
use App\Services\LoginHistoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginHistoryController extends Controller
{
    use ApiResponseTrait;

    protected LoginHistoryService $loginHistoryService;

    /**
     * Constructor injection.
     *
     * @param LoginHistoryService $loginHistoryService
     */
    public function __construct(LoginHistoryService $loginHistoryService)
    {
        $this->loginHistoryService = $loginHistoryService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['user_id', 'status']);
        $perPage = (int) $request->query('per_page', 15);

        $history = $this->loginHistoryService->getLoginHistory($filters, $perPage);

        return $this->successResponse('Login history retrieved successfully', [
            'items' => LoginHistoryResource::collection($history->items()),
            'meta'  => [
                'current_page' => $history->currentPage(),
                'last_page'    => $history->lastPage(),
                'per_page'     => $history->perPage(),
                'total'        => $history->total(),
            ]
        ]);
    }
}
