<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteVisit extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'customer_site_id',
        'assigned_to',
        'visit_number',
        'scheduled_at',
        'check_in_at',
        'check_out_at',
        'check_in_gps',
        'check_out_gps',
        'site_readiness',
        'status',
        'remarks',
        'created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'check_in_at'  => 'datetime',
        'check_out_at' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function customerSite()
    {
        return $this->belongsTo(CustomerSite::class);
    }

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function measurements()
    {
        return $this->hasMany(SiteMeasurement::class, 'site_visit_id');
    }

    public function photos()
    {
        return $this->hasMany(SitePhoto::class, 'site_visit_id');
    }

    public function checklists()
    {
        return $this->hasMany(SiteChecklist::class, 'site_visit_id');
    }

    public function gpsLogs()
    {
        return $this->hasMany(GpsTrackingLog::class, 'site_visit_id');
    }

    public function visitProofs()
    {
        return $this->hasMany(VisitProof::class, 'site_visit_id');
    }
}
