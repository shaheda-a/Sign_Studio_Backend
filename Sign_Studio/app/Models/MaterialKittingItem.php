<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialKittingItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function materialKitting()
    {
        return $this->belongsTo(MaterialKitting::class, 'kitting_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
