<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaterialApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'design_id',
        'material_name',
        'sample_photo_path',
        'brand',
        'customer_approved',
        'remarks',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'approved_at'       => 'datetime',
        'customer_approved' => 'boolean',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }
}
