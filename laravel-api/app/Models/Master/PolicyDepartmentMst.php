<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class PolicyDepartmentMst extends Model
{
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
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'table_name' => 'string',
        'row_id' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
