<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionProof extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['production_plan_id', 'stage_id', 'file_path', 'file_type', 'notes', 'uploaded_by', 'created_by'];

    public function plan()      { return $this->belongsTo(ProductionPlan::class, 'production_plan_id'); }
    public function stage()     { return $this->belongsTo(ProductionStage::class, 'stage_id'); }
    public function uploadedBy(){ return $this->belongsTo(User::class, 'uploaded_by'); }
}
