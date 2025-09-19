<?php

namespace App\Models\History\Master;

use App\Models\Master\AdminMst;
use Illuminate\Database\Eloquent\Model;

class AdminMstHist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'admin_mst_id',
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
        'created_at'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the admin that this history belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function adminMst()
    {
        return $this->belongsTo(AdminMst::class, 'admin_mst_id');
    }

    /**
     * Get the author admin that created this history
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author()
    {
        return $this->belongsTo(AdminMst::class, 'author_id');
    }
}
