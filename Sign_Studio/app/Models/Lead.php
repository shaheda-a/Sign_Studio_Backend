<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'leads';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'customer_id',
        'source_id',
        'assigned_to',
        'architect_id',
        'contractor_id',
        'pipeline_stage_id',
        'lead_number',
        'title',
        'status',
        'lead_score',
        'estimated_value',
        'expected_close_date',
        'lost_reason',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'customer_id'         => 'integer',
        'source_id'           => 'integer',
        'assigned_to'         => 'integer',
        'architect_id'        => 'integer',
        'contractor_id'       => 'integer',
        'pipeline_stage_id'   => 'integer',
        'lead_score'          => 'integer',
        'estimated_value'     => 'double',
        'expected_close_date' => 'date',
        'created_by'          => 'integer',
    ];

    /**
     * Get the customer associated with the lead.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Get the lead source.
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'source_id');
    }

    /**
     * Get the user assigned to this lead.
     */
    public function assignedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the architect associated with the lead.
     */
    public function architect(): BelongsTo
    {
        return $this->belongsTo(Architect::class, 'architect_id');
    }

    /**
     * Get the contractor associated with the lead.
     */
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(Contractor::class, 'contractor_id');
    }

    /**
     * Get the current pipeline stage.
     */
    public function pipelineStage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class, 'pipeline_stage_id');
    }

    /**
     * Get assignment logs.
     */
    public function assignments(): HasMany
    {
        return $this->hasMany(LeadAssignment::class, 'lead_id');
    }

    /**
     * Get pipeline stage history.
     */
    public function pipelineHistory(): HasMany
    {
        return $this->hasMany(LeadPipelineHistory::class, 'lead_id');
    }

    /**
     * Get status workflow history.
     */
    public function statusHistory(): HasMany
    {
        return $this->hasMany(LeadStatusHistory::class, 'lead_id');
    }

    /**
     * Get activities.
     */
    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class, 'lead_id');
    }

    /**
     * Get future follow-up schedules.
     */
    public function followups(): HasMany
    {
        return $this->hasMany(LeadFollowup::class, 'lead_id');
    }

    /**
     * Get scores.
     */
    public function scores(): HasMany
    {
        return $this->hasMany(LeadScore::class, 'lead_id');
    }

    /**
     * Get validation remarks.
     */
    public function validations(): HasMany
    {
        return $this->hasMany(LeadValidation::class, 'lead_id');
    }

    /**
     * Get call logs.
     */
    public function callLogs(): HasMany
    {
        return $this->hasMany(CallLog::class, 'lead_id');
    }

    /**
     * Get booking token advances.
     */
    public function bookingTokens(): HasMany
    {
        return $this->hasMany(BookingToken::class, 'lead_id');
    }
}
