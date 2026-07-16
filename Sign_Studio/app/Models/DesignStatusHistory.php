<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DesignStatusHistory extends Model
{
    public $timestamps = false;

    protected $table = 'design_status_history';

    protected $fillable = [
        'design_id',
        'from_status',
        'to_status',
        'changed_by',
        'notes',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function design()
    {
        return $this->belongsTo(Design::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
