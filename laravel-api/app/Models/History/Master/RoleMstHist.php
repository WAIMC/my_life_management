<?php

namespace App\Models\History\Master;

use App\Models\Master\AdminMst;
use App\Models\Master\RoleMst;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleMstHist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'role_mst_hist';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'role_mst_id',
        'name',
        'permission',
        'is_active',
        'action',
        'author_id',
        'created_at',
    ];

    /**
     * Get the role that this history belongs to
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(RoleMst::class, 'role_mst_id');
    }

    /**
     * Get the author who made this change
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(AdminMst::class, 'author_id');
    }
}
