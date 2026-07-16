<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallationMaterialConfirmation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function installation()
    {
        return $this->belongsTo(Installation::class);
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
