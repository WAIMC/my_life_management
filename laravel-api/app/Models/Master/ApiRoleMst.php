<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiRoleMst extends Model
{
    protected $table = 'api_role_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'api_mst_id',
        'role_mst_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'api_mst_id' => 'integer',
        'role_mst_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the API that owns this relationship.
     *
     * @return BelongsTo
     */
    public function api(): BelongsTo
    {
        return $this->belongsTo(ApiMst::class, 'api_mst_id');
    }

    /**
     * Get the role that owns this relationship.
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(RoleMst::class, 'role_mst_id');
    }
}
