<?php

namespace App\Services;

use App\Models\Template;
use Illuminate\Support\Facades\Auth;

class TemplateService
{
    /**
     * Get templates with optional filtering.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getTemplates(array $filters = [], int $perPage = 15): mixed
    {
        $query = Template::query();

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'desc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a template.
     *
     * @param array $data
     * @return Template
     */
    public function createTemplate(array $data): Template
    {
        $data['created_by'] = Auth::id();
        return Template::create($data);
    }

    /**
     * Get template by ID.
     *
     * @param int $id
     * @return Template
     */
    public function getTemplateById(int $id): Template
    {
        return Template::findOrFail($id);
    }

    /**
     * Update a template.
     *
     * @param int $id
     * @param array $data
     * @return Template
     */
    public function updateTemplate(int $id, array $data): Template
    {
        $template = Template::findOrFail($id);
        $template->update($data);
        return $template;
    }

    /**
     * Delete a template.
     *
     * @param int $id
     * @return bool
     */
    public function deleteTemplate(int $id): bool
    {
        $template = Template::findOrFail($id);
        return $template->delete();
    }

    /**
     * Restore a deleted template.
     *
     * @param int $id
     * @return Template
     */
    public function restoreTemplate(int $id): Template
    {
        $template = Template::withTrashed()->findOrFail($id);
        $template->restore();
        return $template;
    }
}
