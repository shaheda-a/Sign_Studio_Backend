<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesignApproval extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'design_id',
        'revision_id',
        'type',
        'status',
        'customer_approved',
        'approved_by',
        'customer_signature_path',
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

    public function revision()
    {
        return $this->belongsTo(DesignRevision::class, 'revision_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
