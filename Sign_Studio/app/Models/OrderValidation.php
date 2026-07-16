<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderValidation extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'department',
        'status',
        'validated_by',
        'remarks',
        'validated_at',
        'created_by',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function validatedBy()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
