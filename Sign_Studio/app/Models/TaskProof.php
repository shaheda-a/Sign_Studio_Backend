<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TaskProof extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['task_id', 'file_path', 'file_type', 'notes', 'created_by'];

    public function task() { return $this->belongsTo(Task::class); }
}
