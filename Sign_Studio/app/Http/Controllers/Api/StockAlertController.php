<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StockAlertService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class StockAlertController extends Controller
{
    use ApiResponseTrait;

    protected $alertService;

    public function __construct(StockAlertService $alertService)
    {
        $this->alertService = $alertService;
    }

    public function index(Request $request)
    {
        $filters = $request->only(['is_resolved']);
        $perPage = $request->input('per_page', 15);
        $alerts = $this->alertService->getAll($filters, $perPage);
        return $this->successResponse($alerts, 'Stock alerts retrieved successfully.');
    }

    public function show($id)
    {
        $alert = $this->alertService->findById($id);
        return $this->successResponse($alert, 'Stock alert retrieved successfully.');
    }

    public function resolve(Request $request, $id)
    {
        $alert = $this->alertService->resolve($id, $request->user()->id ?? null);
        return $this->successResponse($alert, 'Stock alert resolved successfully.');
    }
}
