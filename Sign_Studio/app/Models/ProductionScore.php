<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductionScore extends Model
{
    protected $fillable = ['production_plan_id', 'quality_score', 'efficiency_score', 'on_time_score', 'overall_score', 'scored_by', 'created_by'];
    protected $casts    = ['overall_score' => 'float'];

    public function plan()     { return $this->belongsTo(ProductionPlan::class, 'production_plan_id'); }
    public function scoredBy() { return $this->belongsTo(User::class, 'scored_by'); }
}
