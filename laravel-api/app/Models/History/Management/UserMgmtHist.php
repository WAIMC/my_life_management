<?php

namespace App\Models\History\Management;

use App\Models\Master\DepartmentMst;
use App\Models\Master\RoleMst;
use App\Models\Management\UserMgmt;
use App\Models\Master\AdminMst;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMgmtHist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_mgmt_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_mgmt_id',
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
        'action',
        'author_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the user that owns the history record
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserMgmt::class, 'user_mgmt_id', 'id');
    }

    /**
     * Get the role that is associated with the history record
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(RoleMst::class, 'role_id', 'id');
    }

    /**
     * Get the department that is associated with the history record
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(DepartmentMst::class, 'department_id', 'id');
    }

    /**
     * Get the author who made the change
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(AdminMst::class, 'author_id', 'id');
    }
}
