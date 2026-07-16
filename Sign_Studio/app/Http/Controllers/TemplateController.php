<?php

namespace App\Http\Controllers;

use App\Http\Requests\TemplateRequest;
use App\Http\Resources\TemplateResource;
use App\Services\TemplateService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TemplateController extends Controller
{
    use ApiResponseTrait;

    protected TemplateService $templateService;

    /**
     * Constructor injection.
     *
     * @param TemplateService $templateService
     */
    public function __construct(TemplateService $templateService)
    {
        $this->templateService = $templateService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['type', 'is_active']);
        $perPage = $request->query('per_page', 15);
        $perPage = ($perPage == -1) ? -1 : (int) $perPage;

        $templates = $this->templateService->getTemplates($filters, $perPage);

        if ($perPage === -1) {
            return $this->successResponse(
                'Templates retrieved successfully',
                TemplateResource::collection($templates)
            );
        }

        return $this->successResponse(
            'Templates retrieved successfully',
            [
                'items' => TemplateResource::collection($templates->items()),
                'meta'  => [
                    'current_page' => $templates->currentPage(),
                    'last_page'    => $templates->lastPage(),
                    'per_page'     => $templates->perPage(),
                    'total'        => $templates->total(),
                ]
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TemplateRequest $request
     * @return JsonResponse
     */
    public function store(TemplateRequest $request): JsonResponse
    {
        $template = $this->templateService->createTemplate($request->validated());

        return $this->successResponse(
            'Template created successfully',
            new TemplateResource($template),
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
        $template = $this->templateService->getTemplateById($id);

        return $this->successResponse(
            'Template retrieved successfully',
            new TemplateResource($template)
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TemplateRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(TemplateRequest $request, int $id): JsonResponse
    {
        $template = $this->templateService->updateTemplate($id, $request->validated());

        return $this->successResponse(
            'Template updated successfully',
            new TemplateResource($template)
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
        $this->templateService->deleteTemplate($id);

        return $this->successResponse('Template deleted successfully');
    }

    /**
     * Restore a deleted resource.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function restore(int $id): JsonResponse
    {
        $template = $this->templateService->restoreTemplate($id);

        return $this->successResponse(
            'Template restored successfully',
            new TemplateResource($template)
        );
    }
}
