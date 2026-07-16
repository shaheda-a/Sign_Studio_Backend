<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Design extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'assigned_to',
        'design_number',
        'title',
        'status',
        'is_locked',
        'due_date',
        'locked_at',
        'locked_by',
        'created_by',
    ];

    protected $casts = [
        'due_date'   => 'date',
        'locked_at'  => 'datetime',
        'is_locked'  => 'boolean',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function revisions()
    {
        return $this->hasMany(DesignRevision::class);
    }

    public function files()
    {
        return $this->hasMany(DesignFile::class);
    }

    public function approvals()
    {
        return $this->hasMany(DesignApproval::class);
    }

    public function sampleApprovals()
    {
        return $this->hasMany(SampleApproval::class);
    }

    public function materialApprovals()
    {
        return $this->hasMany(MaterialApproval::class);
    }

    public function finishApprovals()
    {
        return $this->hasMany(FinishApproval::class);
    }

    public function statusHistory()
    {
        return $this->hasMany(DesignStatusHistory::class);
    }
}
