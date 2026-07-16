<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyChecklist extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    protected $casts = ['task_list' => 'array'];
    
    public function user() { return $this->belongsTo(User::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function reviewer() { return $this->belongsTo(User::class, 'reviewed_by'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
