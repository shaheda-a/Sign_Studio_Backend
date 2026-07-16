<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotationRequest;
use App\Http\Resources\QuotationResource;
use App\Services\QuotationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuotationController extends Controller
{
    use ApiResponseTrait;

    protected QuotationService $quotationService;

    public function __construct(QuotationService $quotationService)
    {
        $this->quotationService = $quotationService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['lead_id', 'customer_id', 'status']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $quotations = $this->quotationService->getQuotations($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Quotations retrieved successfully', QuotationResource::collection($quotations));
        }

        return $this->successResponse('Quotations retrieved successfully', [
            'items' => QuotationResource::collection($quotations->items()),
            'meta'  => [
                'current_page' => $quotations->currentPage(),
                'last_page'    => $quotations->lastPage(),
                'per_page'     => $quotations->perPage(),
                'total'        => $quotations->total(),
            ],
        ]);
    }

    public function store(QuotationRequest $request): JsonResponse
    {
        $quotation = $this->quotationService->createQuotation($request->validated());

        return $this->successResponse('Quotation created successfully', new QuotationResource($quotation), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $quotation = $this->quotationService->getQuotationById($id);

        return $this->successResponse('Quotation retrieved successfully', new QuotationResource($quotation));
    }

    public function update(QuotationRequest $request, int $id): JsonResponse
    {
        $quotation = $this->quotationService->updateQuotation($id, $request->validated());

        return $this->successResponse('Quotation updated successfully', new QuotationResource($quotation));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->quotationService->deleteQuotation($id);

        return $this->successResponse('Quotation deleted successfully');
    }

    public function restore(int $id): JsonResponse
    {
        $quotation = $this->quotationService->restoreQuotation($id);

        return $this->successResponse('Quotation restored successfully', new QuotationResource($quotation));
    }

    public function recalculate(int $id): JsonResponse
    {
        $quotation = $this->quotationService->recalculateTotals($id);

        return $this->successResponse('Quotation totals recalculated', new QuotationResource($quotation));
    }

    public function newVersion(int $id): JsonResponse
    {
        $quotation = $this->quotationService->newVersion($id);

        return $this->successResponse('New quotation version created', new QuotationResource($quotation), Response::HTTP_CREATED);
    }
}
