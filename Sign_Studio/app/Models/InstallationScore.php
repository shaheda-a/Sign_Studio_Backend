<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstallationScore extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }

    public function scoredBy()
    {
        return $this->belongsTo(User::class, 'scored_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
