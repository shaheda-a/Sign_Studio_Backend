<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'order_id',
        'quotation_item_id',
        'description',
        'qty',
        'unit_price',
        'total',
        'created_by',
    ];

    protected $casts = [
        'qty'        => 'float',
        'unit_price' => 'float',
        'total'      => 'float',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function quotationItem()
    {
        return $this->belongsTo(QuotationItem::class, 'quotation_item_id');
    }
}
