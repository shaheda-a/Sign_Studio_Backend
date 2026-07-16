<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function branch() { return $this->belongsTo(Branch::class); }
    public function department() { return $this->belongsTo(Department::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function approvedBy() { return $this->belongsTo(User::class, 'approved_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
