<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerContactRequest;
use App\Http\Resources\CustomerContactResource;
use App\Services\CustomerContactService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerContactController extends Controller
{
    use ApiResponseTrait;

    protected CustomerContactService $customerContactService;

    /**
     * Constructor injection.
     *
     * @param CustomerContactService $customerContactService
     */
    public function __construct(CustomerContactService $customerContactService)
    {
        $this->customerContactService = $customerContactService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['customer_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $contacts = $this->customerContactService->getContacts($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Customer contacts retrieved successfully',
                CustomerContactResource::collection($contacts)
            );
        }

        return $this->successResponse(
            'Customer contacts retrieved successfully',
            [
                'items' => CustomerContactResource::collection($contacts->items()),
                'meta'  => [
                    'current_page' => $contacts->currentPage(),
                    'last_page'    => $contacts->lastPage(),
                    'per_page'     => $contacts->perPage(),
                    'total'        => $contacts->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerContactRequest $request
     * @return JsonResponse
     */
    public function store(CustomerContactRequest $request): JsonResponse
    {
        $contact = $this->customerContactService->createContact($request->validated());

        return $this->successResponse(
            'Customer contact created successfully',
            new CustomerContactResource($contact),
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
        $contact = $this->customerContactService->getContactById($id);

        return $this->successResponse(
            'Customer contact retrieved successfully',
            new CustomerContactResource($contact)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CustomerContactRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CustomerContactRequest $request, int $id): JsonResponse
    {
        $contact = $this->customerContactService->updateContact($id, $request->validated());

        return $this->successResponse(
            'Customer contact updated successfully',
            new CustomerContactResource($contact)
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
        $this->customerContactService->deleteContact($id);

        return $this->successResponse('Customer contact deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $contact = $this->customerContactService->restoreContact($id);

        return $this->successResponse(
            'Customer contact restored successfully',
            new CustomerContactResource($contact)
        );
    }
}
