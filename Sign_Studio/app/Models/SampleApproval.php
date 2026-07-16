<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampleApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'design_id',
        'sample_photo_path',
        'sent_to',
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

    public function sentTo()
    {
        return $this->belongsTo(User::class, 'sent_to');
    }
}
