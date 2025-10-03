<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class AdminDepartmentMst extends Model
{
    protected $table = 'admin_department_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'admin_mst_id',
        'department_mst_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'admin_mst_id' => 'integer',
        'department_mst_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
