<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceAssignment extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function ticket() { return $this->belongsTo(ServiceTicket::class, 'ticket_id'); }
    public function technician() { return $this->belongsTo(User::class, 'technician_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
