<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quotation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_id',
        'customer_id',
        'design_id',
        'quote_number',
        'version',
        'sub_total',
        'discount_amount',
        'tax_amount',
        'grand_total',
        'validity_days',
        'terms_conditions',
        'notes',
        'status',
        'sent_at',
        'approved_at',
        'created_by',
    ];

    protected $casts = [
        'sent_at'         => 'datetime',
        'approved_at'     => 'datetime',
        'sub_total'       => 'float',
        'discount_amount' => 'float',
        'tax_amount'      => 'float',
        'grand_total'     => 'float',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    public function paymentLinks()
    {
        return $this->hasMany(PaymentLink::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
