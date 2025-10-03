<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

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
}
