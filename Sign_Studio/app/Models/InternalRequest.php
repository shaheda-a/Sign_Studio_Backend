<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InternalRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    
    public function raisedBy() { return $this->belongsTo(User::class, 'raised_by'); }
    public function toDepartment() { return $this->belongsTo(Department::class, 'to_department_id'); }
    public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
