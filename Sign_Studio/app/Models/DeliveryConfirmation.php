<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryConfirmation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dispatch()
    {
        return $this->belongsTo(Dispatch::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
