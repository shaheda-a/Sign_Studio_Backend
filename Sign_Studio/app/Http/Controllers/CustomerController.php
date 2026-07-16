<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerController extends Controller
{
    use ApiResponseTrait;

    protected CustomerService $customerService;

    /**
     * Constructor injection.
     *
     * @param CustomerService $customerService
     */
    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'branch_id', 'is_active']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $customers = $this->customerService->getCustomers($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Customers retrieved successfully',
                CustomerResource::collection($customers)
            );
        }

        return $this->successResponse(
            'Customers retrieved successfully',
            [
                'items' => CustomerResource::collection($customers->items()),
                'meta'  => [
                    'current_page' => $customers->currentPage(),
                    'last_page'    => $customers->lastPage(),
                    'per_page'     => $customers->perPage(),
                    'total'        => $customers->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerRequest $request
     * @return JsonResponse
     */
    public function store(CustomerRequest $request): JsonResponse
    {
        $customer = $this->customerService->createCustomer($request->validated());

        return $this->successResponse(
            'Customer created successfully',
            new CustomerResource($customer),
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
        $customer = $this->customerService->getCustomerById($id);

        return $this->successResponse(
            'Customer retrieved successfully',
            new CustomerResource($customer)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CustomerRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CustomerRequest $request, int $id): JsonResponse
    {
        $customer = $this->customerService->updateCustomer($id, $request->validated());

        return $this->successResponse(
            'Customer updated successfully',
            new CustomerResource($customer)
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
        $this->customerService->deleteCustomer($id);

        return $this->successResponse('Customer deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $customer = $this->customerService->restoreCustomer($id);

        return $this->successResponse(
            'Customer restored successfully',
            new CustomerResource($customer)
        );
    }
}
