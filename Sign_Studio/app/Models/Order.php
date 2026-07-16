<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_id',
        'customer_id',
        'branch_id',
        'order_number',
        'total_amount',
        'advance_received',
        'balance_amount',
        'delivery_date',
        'status',
        'is_commercial_locked',
        'commercial_locked_at',
        'commercial_locked_by',
        'created_by',
    ];

    protected $casts = [
        'delivery_date'        => 'date',
        'commercial_locked_at' => 'datetime',
        'is_commercial_locked' => 'boolean',
        'total_amount'         => 'float',
        'advance_received'     => 'float',
        'balance_amount'       => 'float',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function commercialLockedBy()
    {
        return $this->belongsTo(User::class, 'commercial_locked_by');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function jobCard()
    {
        return $this->hasOne(JobCard::class);
    }

    public function validations()
    {
        return $this->hasMany(OrderValidation::class);
    }

    public function paymentLinks()
    {
        return $this->hasMany(PaymentLink::class);
    }
}
