<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectCost extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function order() { return $this->belongsTo(Order::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
