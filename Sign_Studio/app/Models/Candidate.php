<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Candidate extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    
    public function recruitment() { return $this->belongsTo(Recruitment::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function interviews() { return $this->hasMany(Interview::class); }
}
