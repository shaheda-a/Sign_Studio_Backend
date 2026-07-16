<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dispatch extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function packingChecklists()
    {
        return $this->hasMany(PackingChecklist::class);
    }

    public function dispatchItems()
    {
        return $this->hasMany(DispatchItem::class);
    }

    public function dispatchApprovals()
    {
        return $this->hasMany(DispatchApproval::class);
    }

    public function dispatchProofs()
    {
        return $this->hasMany(DispatchProof::class);
    }

    public function vehicleTrackingLogs()
    {
        return $this->hasMany(VehicleTrackingLog::class);
    }

    public function deliveryConfirmation()
    {
        return $this->hasOne(DeliveryConfirmation::class);
    }

    public function installation()
    {
        return $this->hasOne(Installation::class);
    }
}
