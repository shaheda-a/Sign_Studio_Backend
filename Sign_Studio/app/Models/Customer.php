<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'name',
        'email',
        'phone',
        'alternate_phone',
        'gstin',
        'billing_address',
        'billing_city',
        'billing_state',
        'billing_pincode',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'branch_id'  => 'integer',
        'is_active'  => 'integer',
        'created_by' => 'integer',
    ];

    /**
     * Get the branch associated with the customer.
     *
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the contact persons for this customer.
     *
     * @return HasMany
     */
    public function contacts(): HasMany
    {
        return $this->hasMany(CustomerContact::class, 'customer_id');
    }

    /**
     * Get the active sites/locations for this customer.
     *
     * @return HasMany
     */
    public function sites(): HasMany
    {
        return $this->hasMany(CustomerSite::class, 'customer_id');
    }

    /**
     * Get referrals made by this customer.
     *
     * @return HasMany
     */
    public function referrals(): HasMany
    {
        return $this->hasMany(CustomerReferral::class, 'customer_id');
    }
}
