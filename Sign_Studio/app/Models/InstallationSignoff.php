<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InstallationSignoff extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function installation()
    {
        return $this->belongsTo(Installation::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
