<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerFeedback extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'customer_feedbacks';
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function collectedBy() { return $this->belongsTo(User::class, 'collected_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
