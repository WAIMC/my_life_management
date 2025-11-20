<?php

namespace App\Models\Management;

use App\Models\History\Management\UserMgmtHist;
use App\Traits\HasSoftDelete;
use App\Traits\HasStatus;
use App\Traits\HasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserMgmt extends Model
{
    use HasSoftDelete, HasStatus, HasHistory;

    protected $table = 'user_mgmt';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
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
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'email' => 'string',
        'user_name' => 'string',
        'password' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'address' => 'string',
        'phone_number' => 'string',
        'birth' => 'datetime',
        'gender' => 'integer',
        'status' => 'integer',
        'is_active' => 'boolean',
        'avatar' => 'string',
        'email_verified_at' => 'datetime',
        'remember_token' => 'string',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the history records for the user.
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(UserMgmtHist::class, 'user_mgmt_id');
    }
}
