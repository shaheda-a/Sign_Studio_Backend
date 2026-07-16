<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\OrderResource;
use App\Services\OrderService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    use ApiResponseTrait;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['customer_id', 'branch_id', 'status']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $orders = $this->orderService->getOrders($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse('Orders retrieved successfully', OrderResource::collection($orders));
        }

        return $this->successResponse('Orders retrieved successfully', [
            'items' => OrderResource::collection($orders->items()),
            'meta'  => [
                'current_page' => $orders->currentPage(),
                'last_page'    => $orders->lastPage(),
                'per_page'     => $orders->perPage(),
                'total'        => $orders->total(),
            ],
        ]);
    }

    public function store(OrderRequest $request): JsonResponse
    {
        $order = $this->orderService->createOrder($request->validated());

        return $this->successResponse('Order created successfully', new OrderResource($order), Response::HTTP_CREATED);
    }

    public function show(int $id): JsonResponse
    {
        $order = $this->orderService->getOrderById($id);

        return $this->successResponse('Order retrieved successfully', new OrderResource($order));
    }

    public function update(OrderRequest $request, int $id): JsonResponse
    {
        $order = $this->orderService->updateOrder($id, $request->validated());

        return $this->successResponse('Order updated successfully', new OrderResource($order));
    }

    public function destroy(int $id): JsonResponse
    {
        $this->orderService->deleteOrder($id);

        return $this->successResponse('Order deleted successfully');
    }

    public function restore(int $id): JsonResponse
    {
        $order = $this->orderService->restoreOrder($id);

        return $this->successResponse('Order restored successfully', new OrderResource($order));
    }

    public function lockCommercial(int $id): JsonResponse
    {
        $order = $this->orderService->lockCommercial($id);

        return $this->successResponse('Order commercially locked', new OrderResource($order));
    }
}
