<?php

namespace App\Models\History\Master;

use Illuminate\Database\Eloquent\Model;

class PolicyDepartmentMstHist extends Model
{
    protected $table = 'policy_department_mst_hist';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'policy_department_mst_id',
        'table_name',
        'row_id',
        'action',
        'author_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'policy_department_mst_id' => 'integer',
        'table_name' => 'integer',
        'row_id' => 'integer',
        'action' => 'integer',
        'author_id' => 'integer',
        'created_at' => 'datetime',
    ];
}
