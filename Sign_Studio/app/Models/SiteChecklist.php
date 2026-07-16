<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SiteChecklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_visit_id',
        'item_name',
        'is_completed',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];

    public function siteVisit()
    {
        return $this->belongsTo(SiteVisit::class);
    }
}
