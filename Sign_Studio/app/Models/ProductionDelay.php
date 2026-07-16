<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionDelay extends Model
{
    public $timestamps = false;
    protected $fillable = ['production_plan_id', 'stage_id', 'delay_reason', 'delay_hours', 'reported_by', 'created_by'];
    protected $casts    = ['delay_hours' => 'float', 'created_at' => 'datetime'];

    public function plan()       { return $this->belongsTo(ProductionPlan::class, 'production_plan_id'); }
    public function stage()      { return $this->belongsTo(ProductionStage::class, 'stage_id'); }
    public function reportedBy() { return $this->belongsTo(User::class, 'reported_by'); }
}
