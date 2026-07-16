<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookingTokenRequest;
use App\Http\Resources\BookingTokenResource;
use App\Services\BookingTokenService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BookingTokenController extends Controller
{
    use ApiResponseTrait;

    protected BookingTokenService $bookingTokenService;

    /**
     * Constructor injection.
     *
     * @param BookingTokenService $bookingTokenService
     */
    public function __construct(BookingTokenService $bookingTokenService)
    {
        $this->bookingTokenService = $bookingTokenService;
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

        $tokens = $this->bookingTokenService->getTokens($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Booking tokens retrieved successfully',
                BookingTokenResource::collection($tokens)
            );
        }

        return $this->successResponse(
            'Booking tokens retrieved successfully',
            [
                'items' => BookingTokenResource::collection($tokens->items()),
                'meta'  => [
                    'current_page' => $tokens->currentPage(),
                    'last_page'    => $tokens->lastPage(),
                    'per_page'     => $tokens->perPage(),
                    'total'        => $tokens->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BookingTokenRequest $request
     * @return JsonResponse
     */
    public function store(BookingTokenRequest $request): JsonResponse
    {
        $token = $this->bookingTokenService->createToken($request->validated());

        return $this->successResponse(
            'Booking token created successfully',
            new BookingTokenResource($token),
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
        $token = $this->bookingTokenService->getTokenById($id);

        return $this->successResponse(
            'Booking token retrieved successfully',
            new BookingTokenResource($token)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BookingTokenRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(BookingTokenRequest $request, int $id): JsonResponse
    {
        $token = $this->bookingTokenService->updateToken($id, $request->validated());

        return $this->successResponse(
            'Booking token updated successfully',
            new BookingTokenResource($token)
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
        $this->bookingTokenService->deleteToken($id);

        return $this->successResponse('Booking token deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $token = $this->bookingTokenService->restoreToken($id);

        return $this->successResponse(
            'Booking token restored successfully',
            new BookingTokenResource($token)
        );
    }
}
