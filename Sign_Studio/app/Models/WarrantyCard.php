<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarrantyCard extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function issuedBy() { return $this->belongsTo(User::class, 'issued_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
