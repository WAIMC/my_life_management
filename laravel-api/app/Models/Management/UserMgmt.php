<?php

namespace App\Models\Management;

use App\Models\Master\DepartmentMst;
use App\Models\Master\RoleMst;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMgmt extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'role_id',
        'department_id',
        'email',
        'user_name',
        'password',
        'first_name',
        'last_name',
        'address',
        'phone_number',
        'birth',
        'gender',
        'status',
        'is_active',
        'avatar',
        'email_verified_at',
        'remember_token',
        'created_at',
        'updated_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(RoleMst::class, 'role_id');
    }

    /**
     * Get the department that owns the user.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(DepartmentMst::class, 'department_id');
    }
}
