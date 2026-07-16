<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\LeadAssignment;
use App\Models\LeadPipelineHistory;
use App\Models\LeadStatusHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LeadService
{
    /**
     * Get list of leads.
     *
     * @param array $filters
     * @param int $perPage
     * @return mixed
     */
    public function getLeads(array $filters = [], int $perPage = 15): mixed
    {
        $query = Lead::query()->with(['customer', 'source', 'assignedUser', 'pipelineStage']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('lead_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->where('assigned_to', $filters['assigned_to']);
        }

        if (!empty($filters['pipeline_stage_id'])) {
            $query->where('pipeline_stage_id', $filters['pipeline_stage_id']);
        }

        if ($perPage === -1) {
            return $query->orderBy('id', 'desc')->get();
        }

        return $query->orderBy('id', 'desc')->paginate($perPage);
    }

    /**
     * Create a lead record.
     *
     * @param array $data
     * @return Lead
     */
    public function createLead(array $data): Lead
    {
        return DB::transaction(function () use ($data) {
            $data['created_by'] = Auth::id();

            if (empty($data['lead_number'])) {
                $data['lead_number'] = 'LD-' . strtoupper(Str::random(6)) . '-' . time();
            }

            $lead = Lead::create($data);

            if (!empty($lead->pipeline_stage_id)) {
                LeadPipelineHistory::create([
                    'lead_id'       => $lead->id,
                    'from_stage_id' => null,
                    'to_stage_id'   => $lead->pipeline_stage_id,
                    'changed_by'    => Auth::id(),
                    'notes'         => 'Lead created with initial stage',
                ]);
            }

            LeadStatusHistory::create([
                'lead_id'     => $lead->id,
                'from_status' => null,
                'to_status'   => $lead->status ?? 'new',
                'changed_by'  => Auth::id(),
                'notes'       => 'Lead created',
            ]);

            return $lead;
        });
    }

    /**
     * Get lead by ID with loaded relations.
     *
     * @param int $id
     * @return Lead
     */
    public function getLeadById(int $id): Lead
    {
        return Lead::with([
            'customer', 'source', 'assignedUser', 'architect', 'contractor', 'pipelineStage',
            'assignments.assignedFromUser', 'assignments.assignedToUser',
            'pipelineHistory.fromStage', 'pipelineHistory.toStage', 'pipelineHistory.changer',
            'statusHistory.changer', 'activities.user', 'followups.assignedUser', 'scores.scoringUser',
            'validations.validator', 'callLogs.user', 'bookingTokens.receivedByUser'
        ])->findOrFail($id);
    }

    /**
     * Update a lead.
     *
     * @param int $id
     * @param array $data
     * @return Lead
     */
    public function updateLead(int $id, array $data): Lead
    {
        return DB::transaction(function () use ($id, $data) {
            $lead = Lead::findOrFail($id);

            $oldStageId = $lead->pipeline_stage_id;
            $oldStatus = $lead->status;

            $lead->update($data);

            if (isset($data['pipeline_stage_id']) && (int) $data['pipeline_stage_id'] !== (int) $oldStageId) {
                LeadPipelineHistory::create([
                    'lead_id'       => $lead->id,
                    'from_stage_id' => $oldStageId,
                    'to_stage_id'   => $data['pipeline_stage_id'],
                    'changed_by'    => Auth::id(),
                    'notes'         => 'Stage updated manually',
                ]);
            }

            if (isset($data['status']) && $data['status'] !== $oldStatus) {
                LeadStatusHistory::create([
                    'lead_id'     => $lead->id,
                    'from_status' => $oldStatus,
                    'to_status'   => $data['status'],
                    'changed_by'  => Auth::id(),
                    'notes'       => 'Status updated manually',
                ]);
            }

            return $lead;
        });
    }

    /**
     * Assign a lead to another user.
     *
     * @param int $leadId
     * @param int $assignedTo
     * @param string|null $reason
     * @return Lead
     */
    public function assignLead(int $leadId, int $assignedTo, ?string $reason = null): Lead
    {
        return DB::transaction(function () use ($leadId, $assignedTo, $reason) {
            $lead = Lead::findOrFail($leadId);
            $oldAssignee = $lead->assigned_to;

            if ((int) $oldAssignee === (int) $assignedTo) {
                return $lead;
            }

            $lead->update(['assigned_to' => $assignedTo]);

            LeadAssignment::create([
                'lead_id'       => $lead->id,
                'assigned_from' => $oldAssignee,
                'assigned_to'   => $assignedTo,
                'reason'        => $reason,
                'assigned_at'   => now(),
                'created_by'    => Auth::id(),
            ]);

            return $lead;
        });
    }

    /**
     * Change a lead pipeline stage.
     *
     * @param int $leadId
     * @param int $toStageId
     * @param string|null $notes
     * @return Lead
     */
    public function transitionStage(int $leadId, int $toStageId, ?string $notes = null): Lead
    {
        return DB::transaction(function () use ($leadId, $toStageId, $notes) {
            $lead = Lead::findOrFail($leadId);
            $oldStageId = $lead->pipeline_stage_id;

            if ((int) $oldStageId === (int) $toStageId) {
                return $lead;
            }

            $lead->update(['pipeline_stage_id' => $toStageId]);

            LeadPipelineHistory::create([
                'lead_id'       => $lead->id,
                'from_stage_id' => $oldStageId,
                'to_stage_id'   => $toStageId,
                'changed_by'    => Auth::id(),
                'notes'         => $notes,
            ]);

            return $lead;
        });
    }

    /**
     * Change lead status.
     *
     * @param int $leadId
     * @param string $toStatus
     * @param string|null $notes
     * @return Lead
     */
    public function transitionStatus(int $leadId, string $toStatus, ?string $notes = null): Lead
    {
        return DB::transaction(function () use ($leadId, $toStatus, $notes) {
            $lead = Lead::findOrFail($leadId);
            $oldStatus = $lead->status;

            if ($oldStatus === $toStatus) {
                return $lead;
            }

            $lead->update(['status' => $toStatus]);

            LeadStatusHistory::create([
                'lead_id'     => $lead->id,
                'from_status' => $oldStatus,
                'to_status'   => $toStatus,
                'changed_by'  => Auth::id(),
                'notes'       => $notes,
            ]);

            return $lead;
        });
    }

    /**
     * Delete a lead.
     *
     * @param int $id
     * @return bool
     */
    public function deleteLead(int $id): bool
    {
        $lead = Lead::findOrFail($id);
        return $lead->delete();
    }

    /**
     * Restore a deleted lead.
     *
     * @param int $id
     * @return Lead
     */
    public function restoreLead(int $id): Lead
    {
        $lead = Lead::withTrashed()->findOrFail($id);
        $lead->restore();
        return $lead;
    }
}
