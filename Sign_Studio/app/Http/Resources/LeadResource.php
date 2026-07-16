<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'customer_id'         => $this->customer_id,
            'customer'            => new CustomerResource($this->whenLoaded('customer')),
            'source_id'           => $this->source_id,
            'source'              => new LeadSourceResource($this->whenLoaded('source')),
            'assigned_to'         => $this->assigned_to,
            'assigned_user'       => new UserResource($this->whenLoaded('assignedUser')),
            'architect_id'        => $this->architect_id,
            'architect'           => new ArchitectResource($this->whenLoaded('architect')),
            'contractor_id'       => $this->contractor_id,
            'contractor'          => new ContractorResource($this->whenLoaded('contractor')),
            'pipeline_stage_id'   => $this->pipeline_stage_id,
            'pipeline_stage'      => new PipelineStageResource($this->whenLoaded('pipelineStage')),
            'lead_number'         => $this->lead_number,
            'title'               => $this->title,
            'status'              => $this->status,
            'lead_score'          => (int) $this->lead_score,
            'estimated_value'     => (double) $this->estimated_value,
            'expected_close_date' => $this->expected_close_date?->toDateString(),
            'lost_reason'         => $this->lost_reason,
            'created_by'          => $this->created_by,
            'assignments'         => LeadAssignmentResource::collection($this->whenLoaded('assignments')),
            'pipeline_history'    => LeadPipelineHistoryResource::collection($this->whenLoaded('pipelineHistory')),
            'status_history'      => LeadStatusHistoryResource::collection($this->whenLoaded('statusHistory')),
            'activities'          => LeadActivityResource::collection($this->whenLoaded('activities')),
            'followups'           => LeadFollowupResource::collection($this->whenLoaded('followups')),
            'scores'              => LeadScoreResource::collection($this->whenLoaded('scores')),
            'validations'         => LeadValidationResource::collection($this->whenLoaded('validations')),
            'call_logs'           => CallLogResource::collection($this->whenLoaded('callLogs')),
            'booking_tokens'      => BookingTokenResource::collection($this->whenLoaded('bookingTokens')),
            'created_at'          => $this->created_at?->toDateTimeString(),
            'updated_at'          => $this->updated_at?->toDateTimeString(),
            'deleted_at'          => $this->deleted_at?->toDateTimeString(),
        ];
    }
}
