<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerReferralRequest;
use App\Http\Resources\CustomerReferralResource;
use App\Services\CustomerReferralService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerReferralController extends Controller
{
    use ApiResponseTrait;

    protected CustomerReferralService $customerReferralService;

    /**
     * Constructor injection.
     *
     * @param CustomerReferralService $customerReferralService
     */
    public function __construct(CustomerReferralService $customerReferralService)
    {
        $this->customerReferralService = $customerReferralService;
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

        $referrals = $this->customerReferralService->getReferrals($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Customer referrals retrieved successfully',
                CustomerReferralResource::collection($referrals)
            );
        }

        return $this->successResponse(
            'Customer referrals retrieved successfully',
            [
                'items' => CustomerReferralResource::collection($referrals->items()),
                'meta'  => [
                    'current_page' => $referrals->currentPage(),
                    'last_page'    => $referrals->lastPage(),
                    'per_page'     => $referrals->perPage(),
                    'total'        => $referrals->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CustomerReferralRequest $request
     * @return JsonResponse
     */
    public function store(CustomerReferralRequest $request): JsonResponse
    {
        $referral = $this->customerReferralService->createReferral($request->validated());

        return $this->successResponse(
            'Customer referral created successfully',
            new CustomerReferralResource($referral),
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
        $referral = $this->customerReferralService->getReferralById($id);

        return $this->successResponse(
            'Customer referral retrieved successfully',
            new CustomerReferralResource($referral)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CustomerReferralRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(CustomerReferralRequest $request, int $id): JsonResponse
    {
        $referral = $this->customerReferralService->updateReferral($id, $request->validated());

        return $this->successResponse(
            'Customer referral updated successfully',
            new CustomerReferralResource($referral)
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
        $this->customerReferralService->deleteReferral($id);

        return $this->successResponse('Customer referral deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $referral = $this->customerReferralService->restoreReferral($id);

        return $this->successResponse(
            'Customer referral restored successfully',
            new CustomerReferralResource($referral)
        );
    }
}
