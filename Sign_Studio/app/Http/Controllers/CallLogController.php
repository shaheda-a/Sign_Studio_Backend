<?php

namespace App\Http\Controllers;

use App\Http\Requests\CallLogRequest;
use App\Http\Resources\CallLogResource;
use App\Services\CallLogService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CallLogController extends Controller
{
    use ApiResponseTrait;

    protected CallLogService $callLogService;

    /**
     * Constructor injection.
     *
     * @param CallLogService $callLogService
     */
    public function __construct(CallLogService $callLogService)
    {
        $this->callLogService = $callLogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['lead_id', 'customer_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $logs = $this->callLogService->getCallLogs($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Call logs retrieved successfully',
                CallLogResource::collection($logs)
            );
        }

        return $this->successResponse(
            'Call logs retrieved successfully',
            [
                'items' => CallLogResource::collection($logs->items()),
                'meta'  => [
                    'current_page' => $logs->currentPage(),
                    'last_page'    => $logs->lastPage(),
                    'per_page'     => $logs->perPage(),
                    'total'        => $logs->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CallLogRequest $request
     * @return JsonResponse
     */
    public function store(CallLogRequest $request): JsonResponse
    {
        $log = $this->callLogService->createCallLog($request->validated());

        return $this->successResponse(
            'Call log created successfully',
            new CallLogResource($log),
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
        $log = $this->callLogService->getCallLogById($id);

        return $this->successResponse(
            'Call log retrieved successfully',
            new CallLogResource($log)
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
        $this->callLogService->deleteCallLog($id);

        return $this->successResponse('Call log deleted successfully');
    }
}
