<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QuotationItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quotation_id',
        'description',
        'qty',
        'uom',
        'unit_price',
        'discount_percent',
        'tax_rate',
        'tax_amount',
        'total',
        'sort_order',
        'created_by',
    ];

    protected $casts = [
        'qty'              => 'float',
        'unit_price'       => 'float',
        'discount_percent' => 'float',
        'tax_rate'         => 'float',
        'tax_amount'       => 'float',
        'total'            => 'float',
    ];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'quotation_item_id');
    }
}
