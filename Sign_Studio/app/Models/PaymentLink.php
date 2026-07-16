<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLink extends Model
{
    use HasFactory;

    protected $fillable = [
        'quotation_id',
        'order_id',
        'link_reference',
        'amount',
        'gateway',
        'status',
        'qr_code_path',
        'expires_at',
        'paid_at',
        'created_by',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'paid_at'    => 'datetime',
        'amount'     => 'float',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
