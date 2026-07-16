<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskPauseLog extends Model
{
    public $timestamps = false;
    protected $fillable = ['task_id', 'paused_by', 'pause_reason', 'paused_at', 'resumed_at'];
    protected $casts    = ['paused_at' => 'datetime', 'resumed_at' => 'datetime', 'created_at' => 'datetime'];

    public function task()     { return $this->belongsTo(Task::class); }
    public function pausedBy() { return $this->belongsTo(User::class, 'paused_by'); }
}
