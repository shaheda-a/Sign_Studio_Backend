<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function order() { return $this->belongsTo(Order::class); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function branch() { return $this->belongsTo(Branch::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public function items() { return $this->hasMany(InvoiceItem::class); }
    public function receipts() { return $this->hasMany(Receipt::class); }
    public function reminders() { return $this->hasMany(PaymentReminder::class); }
}
