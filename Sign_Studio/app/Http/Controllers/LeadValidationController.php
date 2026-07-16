<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadValidationRequest;
use App\Http\Resources\LeadValidationResource;
use App\Services\LeadValidationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadValidationController extends Controller
{
    use ApiResponseTrait;

    protected LeadValidationService $leadValidationService;

    /**
     * Constructor injection.
     *
     * @param LeadValidationService $leadValidationService
     */
    public function __construct(LeadValidationService $leadValidationService)
    {
        $this->leadValidationService = $leadValidationService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['lead_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $validations = $this->leadValidationService->getValidations($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Lead validations retrieved successfully',
                LeadValidationResource::collection($validations)
            );
        }

        return $this->successResponse(
            'Lead validations retrieved successfully',
            [
                'items' => LeadValidationResource::collection($validations->items()),
                'meta'  => [
                    'current_page' => $validations->currentPage(),
                    'last_page'    => $validations->lastPage(),
                    'per_page'     => $validations->perPage(),
                    'total'        => $validations->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeadValidationRequest $request
     * @return JsonResponse
     */
    public function store(LeadValidationRequest $request): JsonResponse
    {
        $validation = $this->leadValidationService->createValidation($request->validated());

        return $this->successResponse(
            'Lead validation created successfully',
            new LeadValidationResource($validation),
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
        $validation = $this->leadValidationService->getValidationById($id);

        return $this->successResponse(
            'Lead validation retrieved successfully',
            new LeadValidationResource($validation)
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
        $this->leadValidationService->deleteValidation($id);

        return $this->successResponse('Lead validation deleted successfully');
    }
}
