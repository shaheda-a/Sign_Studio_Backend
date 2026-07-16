<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionStage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'production_plan_id', 'stage_master_id', 'stage_name', 'sort_order',
        'planned_start', 'planned_end', 'actual_start', 'actual_end',
        'status', 'assigned_to', 'created_by',
    ];

    protected $casts = [
        'planned_start' => 'datetime',
        'planned_end'   => 'datetime',
        'actual_start'  => 'datetime',
        'actual_end'    => 'datetime',
    ];

    public function plan()       { return $this->belongsTo(ProductionPlan::class, 'production_plan_id'); }
    public function stageMaster(){ return $this->belongsTo(StageMaster::class); }
    public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function proofs()     { return $this->hasMany(ProductionProof::class, 'stage_id'); }
    public function delays()     { return $this->hasMany(ProductionDelay::class, 'stage_id'); }
    public function reworkLogs() { return $this->hasMany(ReworkLog::class, 'stage_id'); }
}
