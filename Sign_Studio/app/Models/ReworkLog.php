<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReworkLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'production_plan_id', 'stage_id', 'reason', 'cost_incurred',
        'time_hours', 'assigned_to', 'status', 'created_by',
    ];

    protected $casts = [
        'cost_incurred' => 'float',
        'time_hours'    => 'float',
    ];

    public function plan()       { return $this->belongsTo(ProductionPlan::class, 'production_plan_id'); }
    public function stage()      { return $this->belongsTo(ProductionStage::class, 'stage_id'); }
    public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to'); }
}
