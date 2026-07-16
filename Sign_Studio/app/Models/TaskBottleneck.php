<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskBottleneck extends Model
{
    public $timestamps = false;
    protected $fillable = ['task_id', 'bottleneck_type', 'description', 'identified_by', 'resolved_by', 'resolved_at', 'created_by'];
    protected $casts    = ['resolved_at' => 'datetime', 'created_at' => 'datetime'];

    public function task()         { return $this->belongsTo(Task::class); }
    public function identifiedBy() { return $this->belongsTo(User::class, 'identified_by'); }
    public function resolvedBy()   { return $this->belongsTo(User::class, 'resolved_by'); }
}
