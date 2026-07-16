<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'job_card_number',
        'qr_code_data',
        'qr_code_path',
        'is_scope_locked',
        'scope_locked_at',
        'scope_locked_by',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'scope_locked_at' => 'datetime',
        'is_scope_locked' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function scopeLockedBy()
    {
        return $this->belongsTo(User::class, 'scope_locked_by');
    }
}
