<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialConsumption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function plan()
    {
        return $this->belongsTo(ProductionPlan::class, 'production_plan_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function stage()
    {
        return $this->belongsTo(ProductionStage::class, 'stage_id');
    }

    public function consumedBy()
    {
        return $this->belongsTo(User::class, 'consumed_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
