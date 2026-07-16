<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceTicket extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function customer() { return $this->belongsTo(Customer::class); }
    public function order() { return $this->belongsTo(Order::class); }
    public function warrantyCard() { return $this->belongsTo(WarrantyCard::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public function assignments() { return $this->hasMany(ServiceAssignment::class, 'ticket_id'); }
    public function resolutions() { return $this->hasMany(ServiceResolution::class, 'ticket_id'); }
    public function quotations() { return $this->hasMany(ServiceQuotation::class, 'ticket_id'); }
}
