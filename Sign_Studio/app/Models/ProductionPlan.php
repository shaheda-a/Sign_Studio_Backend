<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductionPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id', 'job_card_id', 'plan_number', 'start_date', 'end_date',
        'actual_start', 'actual_end', 'status', 'notes', 'created_by',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'actual_start' => 'date',
        'actual_end'   => 'date',
    ];

    public function order()    { return $this->belongsTo(Order::class); }
    public function jobCard()  { return $this->belongsTo(JobCard::class); }
    public function stages()   { return $this->hasMany(ProductionStage::class); }
    public function proofs()   { return $this->hasMany(ProductionProof::class); }
    public function delays()   { return $this->hasMany(ProductionDelay::class); }
    public function scores()   { return $this->hasMany(ProductionScore::class); }
    public function qcChecklists() { return $this->hasMany(QcChecklist::class); }
    public function reworkLogs()   { return $this->hasMany(ReworkLog::class); }
}
