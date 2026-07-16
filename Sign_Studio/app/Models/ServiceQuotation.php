<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceQuotation extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function ticket() { return $this->belongsTo(ServiceTicket::class, 'ticket_id'); }
    public function customer() { return $this->belongsTo(Customer::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
