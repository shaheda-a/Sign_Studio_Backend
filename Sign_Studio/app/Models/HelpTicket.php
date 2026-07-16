<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HelpTicket extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    
    public function raisedBy() { return $this->belongsTo(User::class, 'raised_by'); }
    public function department() { return $this->belongsTo(Department::class); }
    public function assignedTo() { return $this->belongsTo(User::class, 'assigned_to'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
