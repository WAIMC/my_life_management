<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class ApiMst extends Model
{
    protected $table = 'api_mst';

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',
        'name',
        'path',
        'is_active',
        'feature_mst_id',
        'is_delete',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'type' => 'integer',
        'name' => 'string',
        'path' => 'string',
        'is_active' => 'boolean',
        'feature_mst_id' => 'integer',
        'is_delete' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
