<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentScore extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function department() { return $this->belongsTo(Department::class); }
    public function scoredBy() { return $this->belongsTo(User::class, 'scored_by'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
