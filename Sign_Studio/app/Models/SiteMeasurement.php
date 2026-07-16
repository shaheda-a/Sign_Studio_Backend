<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteMeasurement extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_visit_id',
        'sign_position',
        'width',
        'height',
        'sq_ft',
        'depth',
        'unit',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'width'  => 'float',
        'height' => 'float',
        'sq_ft'  => 'float',
        'depth'  => 'float',
    ];

    public function siteVisit()
    {
        return $this->belongsTo(SiteVisit::class);
    }
}
