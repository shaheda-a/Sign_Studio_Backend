<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Http\Resources\LeadResource;
use App\Services\LeadService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LeadController extends Controller
{
    use ApiResponseTrait;

    protected LeadService $leadService;

    /**
     * Constructor injection.
     *
     * @param LeadService $leadService
     */
    public function __construct(LeadService $leadService)
    {
        $this->leadService = $leadService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'status', 'assigned_to', 'pipeline_stage_id']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $leads = $this->leadService->getLeads($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Leads retrieved successfully',
                LeadResource::collection($leads)
            );
        }

        return $this->successResponse(
            'Leads retrieved successfully',
            [
                'items' => LeadResource::collection($leads->items()),
                'meta'  => [
                    'current_page' => $leads->currentPage(),
                    'last_page'    => $leads->lastPage(),
                    'per_page'     => $leads->perPage(),
                    'total'        => $leads->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LeadRequest $request
     * @return JsonResponse
     */
    public function store(LeadRequest $request): JsonResponse
    {
        $lead = $this->leadService->createLead($request->validated());

        return $this->successResponse(
            'Lead created successfully',
            new LeadResource($lead),
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
        $lead = $this->leadService->getLeadById($id);

        return $this->successResponse(
            'Lead retrieved successfully',
            new LeadResource($lead)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LeadRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(LeadRequest $request, int $id): JsonResponse
    {
        $lead = $this->leadService->updateLead($id, $request->validated());

        return $this->successResponse(
            'Lead updated successfully',
            new LeadResource($lead)
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
        $this->leadService->deleteLead($id);

        return $this->successResponse('Lead deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $lead = $this->leadService->restoreLead($id);

        return $this->successResponse(
            'Lead restored successfully',
            new LeadResource($lead)
        );
    }

    /**
     * Assign a lead to another user.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function assign(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'assigned_to' => ['required', 'integer', 'exists:users,id'],
            'reason'      => ['nullable', 'string'],
        ]);

        $lead = $this->leadService->assignLead($id, $request->input('assigned_to'), $request->input('reason'));

        return $this->successResponse(
            'Lead assigned successfully',
            new LeadResource($lead)
        );
    }

    /**
     * Change a lead pipeline stage.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function transitionStage(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'pipeline_stage_id' => ['required', 'integer', 'exists:pipeline_stages,id'],
            'notes'             => ['nullable', 'string'],
        ]);

        $lead = $this->leadService->transitionStage($id, $request->input('pipeline_stage_id'), $request->input('notes'));

        return $this->successResponse(
            'Lead stage transitioned successfully',
            new LeadResource($lead)
        );
    }

    /**
     * Change lead status.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function transitionStatus(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'string', 'max:50'],
            'notes'  => ['nullable', 'string'],
        ]);

        $lead = $this->leadService->transitionStatus($id, $request->input('status'), $request->input('notes'));

        return $this->successResponse(
            'Lead status transitioned successfully',
            new LeadResource($lead)
        );
    }
}
