<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuotationItemRequest;
use App\Http\Resources\QuotationItemResource;
use App\Services\QuotationItemService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class QuotationItemController extends Controller
{
    use ApiResponseTrait;

    protected QuotationItemService $quotationItemService;

    public function __construct(QuotationItemService $quotationItemService)
    {
        $this->quotationItemService = $quotationItemService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['quotation_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $items = $this->quotationItemService->getItems($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Quotation items retrieved successfully', QuotationItemResource::collection($items));
        }

        return $this->successResponse('Quotation items retrieved successfully', [
            'items' => QuotationItemResource::collection($items->items()),
            'meta'  => [
                'current_page' => $items->currentPage(),
                'last_page'    => $items->lastPage(),
                'per_page'     => $items->perPage(),
                'total'        => $items->total(),
            ],
        ]);
    }

    public function store(QuotationItemRequest $request): JsonResponse
    {
        $item = $this->quotationItemService->createItem($request->validated());

        return $this->successResponse('Quotation item created successfully', new QuotationItemResource($item), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $item = $this->quotationItemService->getItemById($id);

        return $this->successResponse('Quotation item retrieved successfully', new QuotationItemResource($item));
    }

    public function update(QuotationItemRequest $request, int $id): JsonResponse
    {
        $item = $this->quotationItemService->updateItem($id, $request->validated());

        return $this->successResponse('Quotation item updated successfully', new QuotationItemResource($item));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->quotationItemService->deleteItem($id);

        return $this->successResponse('Quotation item deleted successfully');
    }
}
