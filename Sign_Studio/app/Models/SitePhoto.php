<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SitePhoto extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'site_visit_id',
        'file_path',
        'file_type',
        'caption',
        'uploaded_at',
        'created_by',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function siteVisit()
    {
        return $this->belongsTo(SiteVisit::class);
    }
}
