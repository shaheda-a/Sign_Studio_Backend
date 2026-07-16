<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GpsTrackingLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'site_visit_id',
        'user_id',
        'latitude',
        'longitude',
        'accuracy',
        'tracked_at',
    ];

    protected $casts = [
        'latitude'   => 'float',
        'longitude'  => 'float',
        'accuracy'   => 'float',
        'tracked_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    public function siteVisit()
    {
        return $this->belongsTo(SiteVisit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
