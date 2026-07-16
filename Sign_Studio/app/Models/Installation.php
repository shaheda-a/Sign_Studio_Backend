<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Installation extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materialConfirmations()
    {
        return $this->hasMany(InstallationMaterialConfirmation::class);
    }

    public function gpsLogs()
    {
        return $this->hasMany(InstallationGpsLog::class);
    }

    public function photos()
    {
        return $this->hasMany(InstallationPhoto::class);
    }

    public function corrections()
    {
        return $this->hasMany(InstallationCorrection::class);
    }

    public function signoff()
    {
        return $this->hasOne(InstallationSignoff::class);
    }

    public function score()
    {
        return $this->hasOne(InstallationScore::class);
    }
}
