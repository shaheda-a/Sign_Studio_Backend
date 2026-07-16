<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delegation extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    
    public function delegatedBy() { return $this->belongsTo(User::class, 'delegated_by'); }
    public function delegatedTo() { return $this->belongsTo(User::class, 'delegated_to'); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
