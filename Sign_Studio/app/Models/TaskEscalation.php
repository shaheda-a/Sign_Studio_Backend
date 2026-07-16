<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskEscalation extends Model
{
    public $timestamps = false;
    protected $fillable = ['task_id', 'escalated_from', 'escalated_to', 'reason', 'level', 'status', 'resolved_at', 'created_by'];
    protected $casts    = ['resolved_at' => 'datetime', 'created_at' => 'datetime'];

    public function task()          { return $this->belongsTo(Task::class); }
    public function escalatedFrom() { return $this->belongsTo(User::class, 'escalated_from'); }
    public function escalatedTo()   { return $this->belongsTo(User::class, 'escalated_to'); }
}
