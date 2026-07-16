<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TdsRecord extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function vendor() { return $this->belongsTo(Vendor::class); }
    public function purchaseOrder() { return $this->belongsTo(PurchaseOrder::class, 'po_id'); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
