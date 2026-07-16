<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'branch_id',
        'department_id',
        'employee_code',
        'name',
        'email',
        'password',
        'phone',
        'designation',
        'date_of_joining',
        'is_active',
        'created_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'branch_id'       => 'integer',
        'department_id'   => 'integer',
        'date_of_joining' => 'date',
        'is_active'       => 'integer',
        'created_by'      => 'integer',
        'password'        => 'hashed',
    ];

    /**
     * Get the branch the user belongs to.
     *
     * @return BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the department the user belongs to.
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    /**
     * Branches created by the user.
     *
     * @return HasMany
     */
    public function createdBranches(): HasMany
    {
        return $this->hasMany(Branch::class, 'created_by');
    }

    /**
     * Departments created by the user.
     *
     * @return HasMany
     */
    public function createdDepartments(): HasMany
    {
        return $this->hasMany(Department::class, 'created_by');
    }
}
