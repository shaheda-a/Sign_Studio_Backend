<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskAcceptance extends Model
{
    protected $table = 'task_acceptance';
    public $timestamps = false;
    protected $fillable = ['task_id', 'user_id', 'status', 'rejection_reason', 'responded_at'];
    protected $casts    = ['responded_at' => 'datetime', 'created_at' => 'datetime'];

    public function task() { return $this->belongsTo(Task::class); }
    public function user() { return $this->belongsTo(User::class); }
}
