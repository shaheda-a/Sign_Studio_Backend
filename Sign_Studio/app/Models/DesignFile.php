<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesignFile extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'design_id',
        'revision_id',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'is_final',
        'uploaded_at',
        'created_by',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
        'is_final'    => 'boolean',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function revision()
    {
        return $this->belongsTo(DesignRevision::class, 'revision_id');
    }
}
