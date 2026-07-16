<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceResolution extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function ticket() { return $this->belongsTo(ServiceTicket::class, 'ticket_id'); }
    public function resolvedBy() { return $this->belongsTo(User::class, 'resolved_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
