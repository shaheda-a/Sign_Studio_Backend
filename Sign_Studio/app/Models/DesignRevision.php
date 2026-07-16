<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesignRevision extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'design_id',
        'revision_number',
        'feedback',
        'requested_by',
        'due_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function requestedBy()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function files()
    {
        return $this->hasMany(DesignFile::class, 'revision_id');
    }
}
