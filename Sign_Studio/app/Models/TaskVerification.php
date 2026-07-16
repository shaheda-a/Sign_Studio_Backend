<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskVerification extends Model
{
    public $timestamps = false;
    protected $fillable = ['task_id', 'verified_by', 'status', 'remarks', 'verified_at', 'created_by'];
    protected $casts    = ['verified_at' => 'datetime', 'created_at' => 'datetime'];

    public function task()       { return $this->belongsTo(Task::class); }
    public function verifiedBy() { return $this->belongsTo(User::class, 'verified_by'); }
}
