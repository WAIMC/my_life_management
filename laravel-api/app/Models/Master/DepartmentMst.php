<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DepartmentMst extends Model
{
    use HasFactory;

    protected $table = 'department_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'code',
        'name',
        'status'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Get the admin department assignments for this department
     *
     * @return HasMany
     */
    public function adminDepartments(): HasMany
    {
        return $this->hasMany(AdminDepartmentMst::class, 'department_id');
    }

    /**
     * Get the department management relations for this department
     *
     * @return HasMany
     */
    public function departmentManagements(): HasMany
    {
        return $this->hasMany(DepartmentManagementMst::class, 'department_id');
    }

    /**
     * Get the history records for this department
     *
     * @return HasMany
     */
    public function history(): HasMany
    {
        return $this->hasMany(DepartmentMstHist::class, 'department_mst_id');
    }
}
