<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentManagementMst extends Model
{
    use HasFactory;

    protected $table = 'department_management_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'department_id',
        'policy_department_id',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the department that owns this management relation
     *
     * @return BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(DepartmentMst::class, 'department_id');
    }

    /**
     * Get the policy department that owns this management relation
     *
     * @return BelongsTo
     */
    public function policyDepartment(): BelongsTo
    {
        return $this->belongsTo(PolicyDepartmentMst::class, 'policy_department_id');
    }
}
