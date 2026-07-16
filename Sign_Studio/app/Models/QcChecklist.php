<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QcChecklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'production_plan_id', 'item_name', 'is_passed', 'rework_required',
        'inspected_by', 'notes', 'photo_path', 'created_by',
    ];

    protected $casts = [
        'is_passed'       => 'boolean',
        'rework_required' => 'boolean',
    ];

    public function plan()        { return $this->belongsTo(ProductionPlan::class, 'production_plan_id'); }
    public function inspectedBy() { return $this->belongsTo(User::class, 'inspected_by'); }
}
