<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentManagementMst extends Model
{
    protected $table = 'department_management_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'department_mst_id',
        'policy_department_mst_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'department_mst_id' => 'integer',
        'policy_department_mst_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the department that owns this relationship.
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(DepartmentMst::class, 'department_mst_id');
    }

    /**
     * Get the policy that owns this relationship.
     *
     * @return BelongsTo
     */
    public function policy(): BelongsTo
    {
        return $this->belongsTo(PolicyDepartmentMst::class, 'policy_department_mst_id');
    }
}
