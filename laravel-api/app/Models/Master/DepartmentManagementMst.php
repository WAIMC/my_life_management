<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

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
}
