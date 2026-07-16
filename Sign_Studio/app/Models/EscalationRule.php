<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EscalationRule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'escalation_rules';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module',
        'trigger_after_hours',
        'escalate_to_role_id',
        'notify_user_id',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'trigger_after_hours' => 'integer',
        'escalate_to_role_id' => 'integer',
        'notify_user_id'      => 'integer',
        'is_active'           => 'integer',
        'created_by'          => 'integer',
    ];

    /**
     * Get the role to escalate to.
     *
     * @return BelongsTo
     */
    public function escalateToRole(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'escalate_to_role_id');
    }

    /**
     * Get the user to notify.
     *
     * @return BelongsTo
     */
    public function notifyUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'notify_user_id');
    }
}
