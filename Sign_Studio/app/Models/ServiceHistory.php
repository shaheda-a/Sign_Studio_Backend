<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceHistory extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'service_history';
    protected $guarded = [];

    public function asset() { return $this->belongsTo(ServiceAsset::class, 'service_asset_id'); }
    public function ticket() { return $this->belongsTo(ServiceTicket::class, 'ticket_id'); }
    public function servicedBy() { return $this->belongsTo(User::class, 'serviced_by'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
