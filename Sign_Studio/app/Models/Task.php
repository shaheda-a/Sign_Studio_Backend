<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id', 'department_id', 'assigned_to', 'task_number',
        'title', 'description', 'priority', 'planned_start', 'planned_end',
        'actual_start', 'actual_end', 'planned_time_hours', 'actual_time_hours',
        'tat_duration_hours', 'status', 'created_by',
    ];

    protected $casts = [
        'planned_start'      => 'datetime',
        'planned_end'        => 'datetime',
        'actual_start'       => 'datetime',
        'actual_end'         => 'datetime',
        'planned_time_hours' => 'float',
        'actual_time_hours'  => 'float',
        'tat_duration_hours' => 'float',
    ];

    public function order()        { return $this->belongsTo(Order::class); }
    public function department()   { return $this->belongsTo(Department::class); }
    public function assignedTo()   { return $this->belongsTo(User::class, 'assigned_to'); }
    public function logs()         { return $this->hasMany(TaskLog::class); }
    public function acceptance()   { return $this->hasMany(TaskAcceptance::class); }
    public function pauseLogs()    { return $this->hasMany(TaskPauseLog::class); }
    public function delays()       { return $this->hasMany(TaskDelay::class); }
    public function escalations()  { return $this->hasMany(TaskEscalation::class); }
    public function bottlenecks()  { return $this->hasMany(TaskBottleneck::class); }
    public function verifications(){ return $this->hasMany(TaskVerification::class); }
    public function proofs()       { return $this->hasMany(TaskProof::class); }
}
