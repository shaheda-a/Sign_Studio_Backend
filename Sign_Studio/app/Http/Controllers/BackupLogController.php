<?php

namespace App\Http\Controllers;

use App\Http\Requests\BackupLogRequest;
use App\Http\Resources\BackupLogResource;
use App\Services\BackupLogService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BackupLogController extends Controller
{
    use ApiResponseTrait;

    protected BackupLogService $backupLogService;

    /**
     * Constructor injection.
     *
     * @param BackupLogService $backupLogService
     */
    public function __construct(BackupLogService $backupLogService)
    {
        $this->backupLogService = $backupLogService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['status']);
        $perPage = (int) $request->query('per_page', 15);

        $logs = $this->backupLogService->getLogs($filters, $perPage);

        return $this->successResponse('Backup logs retrieved successfully', [
            'items' => BackupLogResource::collection($logs->items()),
            'meta'  => [
                'current_page' => $logs->currentPage(),
                'last_page'    => $logs->lastPage(),
                'per_page'     => $logs->perPage(),
                'total'        => $logs->total(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BackupLogRequest $request
     * @return JsonResponse
     */
    public function store(BackupLogRequest $request): JsonResponse
    {
        $log = $this->backupLogService->createLog($request->validated());

        return $this->successResponse(
            'Backup log created successfully',
            new BackupLogResource($log),
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
        $log = $this->backupLogService->getLogById($id);

        return $this->successResponse(
            'Backup log retrieved successfully',
            new BackupLogResource($log)
        );
    }
}
