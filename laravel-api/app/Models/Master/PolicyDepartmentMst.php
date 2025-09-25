<?php

namespace App\Models\Master;

use App\Models\History\Master\PolicyDepartmentMstHist;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PolicyDepartmentMst extends Model
{
    use HasFactory;

    protected $table = 'policy_department_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'table_name',
        'row_id',
        'is_delete',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the department management relations for this policy department
     *
     * @return HasMany
     */
    public function departmentManagements(): HasMany
    {
        return $this->hasMany(DepartmentManagementMst::class, 'policy_department_id');
    }

    /**
     * Get the history records for this policy department
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(PolicyDepartmentMstHist::class, 'policy_department_mst_id');
    }
}
