<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDelay extends Model
{
    protected $fillable = ['task_id', 'delay_reason', 'escalation_level', 'escalated_to', 'created_by'];

    public function task()        { return $this->belongsTo(Task::class); }
    public function escalatedTo() { return $this->belongsTo(User::class, 'escalated_to'); }
}
