<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleTrackingLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }
}
